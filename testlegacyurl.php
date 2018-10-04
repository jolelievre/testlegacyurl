<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use Tests\Integration\PrestaShopBundle\Routing\LegacyUrlConverterTest;

class Testlegacyurl extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'testlegacyurl';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Test legacy url');
        $this->description = $this->l('This module is used to test legacy urls as they are migrated to Symfony.
It displays two lists of links:
- a list of legacy links you can click on to check if they are correctly redirected
- a list of links generated with le legacy Link class and are supposed to be formatted as new Symfony urls
');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('TESTLEGACYURL_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->context->smarty->assign('module_dir', $this->_path);

        $migratedControllers = LegacyUrlConverterTest::getMigratedControllers();

        $convertedActionUrls = [];
        $convertedParameterUrls = [];
        $legacyActionUrls = [];
        $legacyParameterUrls = [];
        $link = new Link();
        foreach ($migratedControllers as $routeName => $migratedController) {
            $controller = $migratedController[1];
            $action = count($migratedController) > 2 ? $migratedController[2] : null;
            $parameters = count($migratedController) > 3 ? $migratedController[3] : [];

            //Generate converted url
            $actionParameters = array_merge([], $parameters);
            if (null !== $action) {
                $actionParameters['action'] = $action;
            }
            $convertedActionUrls[$routeName] = $link->getAdminLink($controller, true, [], $actionParameters);

            //Generate legacy action url
            $legacyActionUrls[$routeName] = $link->getAdminBaseLink() . basename(_PS_ADMIN_DIR_) . '/' .  \Dispatcher::getInstance()->createUrl($controller, null, $actionParameters);

            //Generate parameter urls
            $parameterParameters = array_merge([], $parameters);
            if (null !== $action) {
                $parameterParameters[$action] = true;
            }
            $convertedParameterUrls[$routeName] = $link->getAdminLink($controller, true, [], $parameterParameters);

            //Generate legacy parameter url
            $legacyParameterUrls[$routeName] = $link->getAdminBaseLink() . basename(_PS_ADMIN_DIR_) . '/' .  \Dispatcher::getInstance()->createUrl($controller, null, $parameterParameters);
        }

        $this->context->smarty->assign([
            'convertedActionUrls' => $convertedActionUrls,
            'convertedParameterUrls' => $convertedParameterUrls,
            'legacyActionUrls' => $legacyActionUrls,
            'legacyParameterUrls' => $legacyParameterUrls,
        ]);
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output;
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
