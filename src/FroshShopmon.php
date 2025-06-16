<?php

declare(strict_types=1);

namespace Frosh\Shopmon;

use Frosh\Shopmon\Service\InstallationService;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class FroshShopmon extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        $integrationRepository = $this->container->get('integration.repository');
        $aclRoleRepository = $this->container->get('acl_role.repository');
        $systemConfigService = $this->container->get(SystemConfigService::class);

        $installationService = new InstallationService(
            $integrationRepository,
            $aclRoleRepository,
            $systemConfigService,
        );
        $installationService->install($installContext);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }

        $integrationRepository = $this->container->get('integration.repository');
        $aclRoleRepository = $this->container->get('acl_role.repository');
        $systemConfigService = $this->container->get(SystemConfigService::class);

        $installationService = new InstallationService(
            $integrationRepository,
            $aclRoleRepository,
            $systemConfigService,
        );
        $installationService->uninstall($uninstallContext);
    }
}
