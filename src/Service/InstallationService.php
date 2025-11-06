<?php

declare(strict_types=1);

namespace Frosh\Shopmon\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\DevOps\Environment\EnvironmentHelper;
use Shopware\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use Shopware\Core\Framework\Api\Util\AccessKeyHelper;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Integration\IntegrationDefinition;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class InstallationService
{
    private const INTEGRATION_ID = 'a1b2c3d4e5f6789012345678901234ab';

    public function __construct(
        private readonly EntityRepository $integrationRepository,
        private readonly EntityRepository $aclRepository,
        private readonly SystemConfigService $systemConfigService
    ) {
    }

    public function install(InstallContext $installContext): void
    {
        $this->createIntegration(Context::createDefaultContext());
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        $this->removeIntegration(Context::createDefaultContext());
        $this->removeSystemConfig();
    }

    private function createIntegration(Context $context): void
    {
        $accessKey = EnvironmentHelper::getVariable('SHOPMON_ACCESS_KEY', AccessKeyHelper::generateAccessKey('integration'));
        $secretAccessKey = EnvironmentHelper::getVariable('SHOPMON_ACCESS_SECRET', AccessKeyHelper::generateSecretAccessKey());

        $this->integrationRepository->upsert([
            [
                'id' => self::INTEGRATION_ID,
                'label' => 'FroshShopmon Integration',
                'accessKey' => $accessKey,
                'secretAccessKey' => $secretAccessKey,
                'aclRoles' => [
                    [
                        'id' => self::INTEGRATION_ID,
                        'name' => 'frosh_shopmon_role',
                        'privileges' => [
                            'app:read',
                            'plugin:read',
                            'system_config:read',
                            'scheduled_task:read',
                            'frosh_tools:read',
                            'system:clear:cache',
                            'system:cache:info',
                            'scheduled_task:update'
                        ]
                    ]
                ]
            ]
        ], $context);

        $this->saveIntegrationConfig($accessKey, $secretAccessKey);
    }

    private function removeIntegration(Context $context): void
    {
        try {
            $this->integrationRepository->delete([
                ['id' => self::INTEGRATION_ID]
            ], $context);

            $this->aclRepository->delete([
                ['id' => self::INTEGRATION_ID]
            ], $context);
        } catch (\Exception $e) {
            // Integration might not exist, ignore error
        }
    }

    private function removeSystemConfig(): void
    {
        $this->systemConfigService->delete('FroshShopmon.config.integrationData');
    }

    private function saveIntegrationConfig(string $clientId, string $clientSecret): void
    {
        $appUrl = EnvironmentHelper::getVariable('APP_URL', 'http://localhost');

        $integrationData = [
            'url' => $appUrl,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret
        ];

        $base64Data = base64_encode(json_encode($integrationData, JSON_THROW_ON_ERROR));

        $this->systemConfigService->set('FroshShopmon.config.integrationData', $base64Data);
    }
}
