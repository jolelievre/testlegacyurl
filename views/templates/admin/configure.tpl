{*
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
*}

<div class="panel">
	<h3><i class="icon icon-cog"></i> {l s='Test legacy url' mod='testlegacyurl'}</h3>
	<p>
		<strong>{l s='This module is used to test url migration' mod='testlegacyurl'}</strong><br />
		{l s='Be careful what you click in some url are used to delete, some use IDs that don\'t exist in the database and therefore can not work.' mod='testlegacyurl'}<br />
		{l s='Other urls are only meant to be used by POST or GET and clicking on them won\'t work, focus on the url format in these cases.' mod='testlegacyurl'}
	</p>
	<br />

	<h3>Converted urls with action parameter (e.g: action=save)</h3>
	<p>
		These urls were generated with the Link class using an action parameter:<br />
		<code>
			$link = new Link();
			$linkUrl = $link->getAdminLink('AdminProducts', true, [], ['action' => 'add']);
		</code>
	</p>
	<ul>
		{foreach from=$convertedActionUrls key=route item=url}
			<li>
				<a href="{$url}" target="_blank">
					{$route} : {$url}
				</a>
			</li>
		{/foreach}
	</ul>
	<br />

	<h3>Converted urls with boolean parameter (e.g: save=true)</h3>
	<p>
		These urls were generated with the Link class using a boolean parameter:<br />
		<code>
			$link = new Link();
			$linkUrl = $link->getAdminLink('AdminProducts', true, [], ['add' => true]);
		</code>
	</p>
	<ul>
		{foreach from=$convertedParameterUrls key=route item=url}
			<li>
				<a href="{$url}" target="_blank">
					{$route} : {$url}
				</a>
			</li>
		{/foreach}
	</ul>
	<br />

	<h3>Legacy urls with action parameter (e.g: action=save)</h3>
	<p>
		These urls were generated with the Dispatcher to keep the legacy format class using an action parameter:<br />
		<code>
			$linkUrl = \Dispatcher::getInstance()->createUrl('AdminProducts', null, ['action' => 'add']);
		</code>
		<br />
		Clinking on these legacy links will redirect you to the migrated url.
	</p>
	<ul>
		{foreach from=$legacyActionUrls key=route item=url}
			<li>
				<a href="{$url}" target="_blank">
					{$route} : {$url}
				</a>
			</li>
		{/foreach}
	</ul>
	<br />

	<h3>Legacy urls with boolean parameter (e.g: save=true)</h3>
	<p>
		These urls were generated with the Dispatcher to keep the legacy format class using a boolean parameter:<br />
		<code>
			$linkUrl = \Dispatcher::getInstance()->createUrl('AdminProducts', null, ['add' => true]);
		</code>
		<br />
		Clinking on these legacy links will redirect you to the migrated url.
	</p>
	<ul>
		{foreach from=$legacyParameterUrls key=route item=url}
			<li>
				<a href="{$url}" target="_blank">
					{$route} : {$url}
				</a>
			</li>
		{/foreach}
	</ul>
	<br />
</div>
