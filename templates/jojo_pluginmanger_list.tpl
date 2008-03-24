{foreach item=p from=$plugins}
<a href="{$p.url}"><h2>{$p.pd_name}</h2></a>
<a href="{$p.url}">Version {$p.version}</a>
<table style='width: 100%'>
{if $p.pd_description}
    <tr class="description">
        <td>
            {$p.pd_description}
        </td>
{if $p.pd_image}
{if $reflect}
        <td style='width: 140px; padding: 0; background: white; border: none;' rowspan='2' valign='top'>
            <a href="images/default/plugin_detailss/{$p.pd_image}"><img style='float:right' src='reflectall/w140/plugin_detailss/{$p.pd_image}' /></a>
{else}
        <td style='width: 150px; padding: 0; background: white; border: none;' rowspan='2' valign='top'>
            <a href="images/default/plugin_detailss/{$p.pd_image}"><img style='float:right' src='images/w150/plugin_detailss/{$p.pd_image}' /></a>
{/if}
        </td>
{/if}
    </tr>
{/if}

    <tr>
        <td valign='top'>Download:<br/>
            {if $p.pv_file_zip}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_zip}">{$p.pv_file_zip}</a><br />{/if}
            {if $p.pv_file_7z}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_7z}">{$p.pv_file_7z}</a><br />{/if}
            {if $p.pv_file_tgz}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_tgz}">{$p.pv_file_tgz}</a><br />{/if}
            {if $p.pd_svn}<span>SVN Repository: </span><a href="{$p.pd_svn}" rel='nofollow'>{$p.pd_svn}</a><br />{/if}
        </td>
    </tr>
</table>
{/foreach}
