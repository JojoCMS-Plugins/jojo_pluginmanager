<table>
    <tr>
        <td valign='top'>
            {if $plugin.pd_description}<div class="description" style='float:left; clear: left;'>{$plugin.pd_description}</div>{/if}
            <div class="plugindetails" style='float: left;  clear: left;'>
                {if $plugin.pd_author}<span>Author: </span>{$plugin.pd_author}<br />{/if}
                {if $plugin.pd_website}<span>Website: </span><a href="{$plugin.pd_website}">{$plugin.pd_website}</a><br />{/if}
                {if $plugin.pd_demolink}<span>Demolink: </span><a href="{$plugin.pd_demolink}">{$plugin.pd_demolink}</a><br />{/if}
                {if $plugin.pd_license}<span>License: </span>{$plugin.pd_license}<br />{/if}
                {if $plugin.pd_svn}<span>SVN Repository: </span><a href="{$plugin.pd_svn}" rel='nofollow'>{$plugin.pd_svn}</a><br />{/if}
            </div>
        </td>

{if $plugin.pd_image}
{if $reflect}
        <td style='width: 145px; padding: 0; background: white; border: none;' valign='top'>
            <a href="images/default/plugin_detailss/{$plugin.pd_image}"><img style='float:right' src='reflectall/w140/plugin_detailss/{$plugin.pd_image}' /></a>
{else}
        <td style='width: 155px; padding: 0; background: white; border: none;' valign='top'>
            <a href="images/default/plugin_detailss/{$plugin.pd_image}"><img style='float:right' src='images/w150/plugin_detailss/{$plugin.pd_image}' /></a>
{/if}
        </td>
{/if}
    </tr>
</table>

<div style='width: 100%; clear: both'></div>
{foreach item=p from=$versions}
    <table class="detailsTable" >
        <caption>Version {$p.pv_version}</caption>
        <tr>
            <th>Download</th>
        </tr>
        <tr>
            <td valign='top'>{if $p.pv_downloads}{$p.pv_downloads}<br />{/if}
            {if $p.pv_file_zip}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_zip}">{$p.pv_file_zip}</a><br />{/if}
            {if $p.pv_file_7z}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_7z}">{$p.pv_file_7z}</a><br /> {/if}
            {if $p.pv_file_tgz}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_tgz}">{$p.pv_file_tgz}</a>{/if}
            </td>
        </tr>
        <tr class="description">
            <td colspan="2">
                <span>Released: </span>{$p.pv_datetime}<br />
                {if $p.pv_releasenotes}<span>Releasenotes:</span><br/>{$p.pv_releasenotes}<br />{/if}
            </td>
        </tr>

        <tr>
            <td colspan="4"><a href="" onclick="$('#add-comment').show(); return false;">Write a Comment about this version</a>
            <br style="clear:both"; /></td>
        </tr>
    </table>

    <div id="add-comment" class="comment hidden">
        <h3>Write a Comment</h3>
        {if $success}<div id="success">{$success}</div>{/if}
        {if $message}<div id="message">{$message}</div>{/if}
        <form class="commentform" action="{$RELATIVE_URL}" method="post" name="commentform" enctype="multipart/form-data" onsubmit="return checkForm(this)">
            <label for="name">Name</label><input value="{if $fields.name}{$fields.name}{/if}" type="text" name="name" size="45" /> <br />
            <label for="email">Email (not for public visible)</label><input value="{if $fields.email}{$fields.email}{/if}" type="text" name="email" size="45" /><br />
            <input type="hidden"  value="{$p.pluginid}" name="pluginversionid"/>
            <label for="comment">Comment</label><br />
            <textarea cols="60" rows="5" name="comment">{if $fields.comment}{$fields.comment}{/if}</textarea> <br />
            <br /><button type="submit" name="btn_save" value="Save Comment &gt;&gt;" />Send Comment</button>
        </form>
    </div>

    {foreach item=p from=$plugincomments}
    <div class="comments">
        <span>From: </span>{if $p.pc_name}{$p.pc_name} {/if} </span><br />
        <span>Date: </span>{if $p.date}{$p.date} {/if} <br />
        <span>Comment: </span>{$p.pc_comment} <br /> <br />
    </div>
    {/foreach}

{/foreach}


<script type="text/javascript">
{literal}
function showComments(comment) {
 var divs = getElementsByClassName("comment");
  for (var i=0; i<divs.length; i++) {
    addClass(divs[i],'hidden');
  }
  removeclass('comments-'+comment,'hidden');
}
{/literal}
</script>