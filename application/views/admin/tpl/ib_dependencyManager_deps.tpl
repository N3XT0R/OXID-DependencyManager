[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="ib_dependencyManager_dependencies">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

[{assign var="aDependencies" value=$oView->getDependencies()}]


<table border="0" width="100%">
    <thead>
        <tr>
            <th>[{oxmultilang ident="ib_DependencyManager_MODULE_NAME"}]</th>
            <th>[{oxmultilang ident="ib_DependencyManager_MODULE_MIN_VERSION"}]</th>
            <th>[{oxmultilang ident="ib_DependencyManager_MODULE_MAX_VERSION"}]</th>
            <th>[{oxmultilang ident="ib_DependencyManager_MODULE_STATUS"}]</th>
        </tr>
    </thead>
    <tbody>
    [{foreach from=$aDependencies item=aDependency key=sModule}]
        <tr>
            <td align="center"valign="center" class="edittext">[{$sModule}]</td>
            <td align="center"valign="center" class="edittext">[{$aDependency.minVersion}]</td>
            <td align="center"valign="center" class="edittext">[{$aDependency.maxVersion}]</td>
            <td align="center"valign="center" class="edittext">
                [{if $oView->getModuleExists($sModule) eq false}]
                    <strong style="color: red;">[{oxmultilang ident="ib_DependencyManager_MODULE_STATUS_NOT_EXISTS"}]</strong>
                [{else}]
                    [{if $oView->getIsModuleCompatible($sModule, $aDependency) eq true}]
                        [{if $oView->getIsModuleActive($sModule) eq true}]
                            <strong style="color: darkgreen;">[{oxmultilang ident="ib_DependencyManager_MODULE_STATUS_ACTIVE"}]</strong>
                        [{else}]
                            <strong style="color: red;">[{oxmultilang ident="ib_DependencyManager_MODULE_STATUS_NOT_ACTIVE"}]</strong>
                        [{/if}]
                    [{else}]
                        <strong style="color: red;">[{oxmultilang ident="ib_DependencyManager_MODULE_STATUS_NOT_COMPATIBLE"}]</strong>
                    [{/if}]
                [{/if}]
            </td>
        </tr>
    [{/foreach}]
    </tbody>
</table>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]