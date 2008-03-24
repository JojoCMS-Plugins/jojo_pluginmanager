{if $action == 'all'}
<div id="tabsJ">
    <ul>
        <li><a href="plugins/name" rel="nofollow"><span>Name</span></a></li>
        <li><a href="plugins/updated" rel="nofollow"><span>Last Updated</span></a></li>
        <li><a href="plugins/rating" rel="nofollow"><span>Rating</span></a></li>
        <li><a href="plugins/downloads" rel="nofollow"><span>Download</span></a></li>
        <li><a href="plugins/status" rel="nofollow"><span>Status</span></a></li>
        <li><a href="plugins/tags" rel="nofollow"><span>Tags</span></a></li>
    </ul>
</div>
<br style="clear:both;">

    <table class="detailsTable">
        <tr>
            <th>Name</th>
            <th>Rating</th>
            <th>Download</th>
        </tr>
        {foreach item=p from=$plugins}
        <tr>
            <td valign='top'><a href="{$p.url}">{if $p.pd_name} {$p.pd_name}{/if}</a><br />{if $p.pv_status}{$p.pv_status} Version{/if}</td>
            <td valign='top' class="stars">{if $p.stars}{$p.stars}{/if}</td>
            <td valign='top'>{if $p.downloads}{$p.downloads}<br />{/if}
                {if $p.pv_file_zip}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_zip}">{$p.pv_file_zip}</a><br />{/if}
                {if $p.pv_file_7z}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_7z}">{$p.pv_file_7z}</a><br /> {/if}
                {if $p.pv_file_tgz}<a href="plugins/download/{$p.pluginversionid}/{$p.pv_file_tgz}">{$p.pv_file_tgz}</a>{/if}
            </td>
        </tr>

        <tr class="description">
            <td colspan="3">
                {if $p.date}<span>Last Updated: </span>{$p.date}<br />{/if}
                {if $p.pd_description}<span>Description: </span>{$p.pd_description}<br />{/if}
                {if $p.tags}<span>Tags:</span> {foreach item=tag from=$p.tags}<a href="plugins/tags/{$tag}">{$tag}</a> {/foreach}{/if}
            </td>
        </tr>
        {/foreach}
    </table>

<div id="page">

{if $previous neq 0}
<a href="plugins/{$previous}/{if $cat}{$cat}/{/if}previous" alt="" title="">Previous Page</a>
{/if}
{if $previous neq 0 and $next neq 0}
| |
{/if}
{if $next neq 0}
<a href="plugins/{$next}/{if $cat}{$cat}/{/if}next" alt="" title="">Next Page</a>
{/if}
</div>

{elseif $action == 'tagcloud'}
    <div id="tabsJ">
        <ul>
        <li><a href="plugins/name" rel="nofollow"><span>Name</span></a></li>
        <li><a href="plugins/updated" rel="nofollow"><span>Last Updated</span></a></li>
        <li><a href="plugins/rating" rel="nofollow"><span>Rating</span></a></li>
        <li><a href="plugins/downloads" rel="nofollow"><span>Download</span></a></li>
        <li><a href="plugins/status" rel="nofollow"><span>Status</span></a></li>
        <li><a href="plugins/tags" rel="nofollow"><span>Tags</span></a></li>
        </ul>
    </div>
    <br style="clear:both;">
    <div class="description">
        {foreach item=weight key=tag from=$tags}<a style='font-size:{$weight*16+14}px; text-decoration: underline; margin: 3px;' href="plugins/tags/{$tag}" rel="nofollow">{$tag}</a> {/foreach}
    </div>
{elseif $action eq 'details'}




{else if $action eq 'allComments'}
    {if $plugindetails}
        <div class="description">
        {if $plugindetails[0].pd_description}{$plugindetails[0].pd_description}{/if}
        </div>
        <div class="plugindetails">
        {if $plugindetails[0].pd_author}<span>Author: </span>{$plugindetails[0].pd_author}  {/if}<br />
        {if $plugindetails[0].pd_website}<span>Website: </span>{$plugindetails[0].pd_website}  {/if}<br />
        {if $plugindetails[0].pd_demolink}<span>Demolink: </span>{$plugindetails[0].pd_demolink}  {/if}<br />
        {if $plugindetails[0].pd_license}<span>license: </span>{$plugindetails[0].pd_license}  {/if}<br />
        {if $plugindetails[0].tags}<span>Tags: </span> {foreach item=tag from=$plugindetails[0].tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach}	{/if}
        </div>


    <table class="detailsTable">
        <tr>
            <td>{if $plugindetails[0].pv_version}<span>Version: {$plugindetails[0].pv_version} {/if}</span></td>
            <td>{if $plugindetails[0].pv_status}<span>Status: {$plugindetails[0].pv_status}  {/if}</span>		</td>
            <td class="stars">{if $plugindetails[0].stars}{$plugindetails[0].stars} {/if}</td>
            <td>{if $plugindetails[0].pv_downloads}{$plugindetails[0].pv_downloads} {/if}<br />
            {if $plugindetails[0].pv_file_zip}<a href="plugins/{$plugincomments[0].pv_file_zip}/{$p.pluginversionid}/download">{$plugindetails[0].pv_file_zip} </a><br />{/if}
            {if $plugindetails[0].pv_file_7z}<a href="plugins/{$plugincomments[0].pv_file_7z}/{$p.pluginversionid}/download">{$plugindetails[0].pv_file_7z}</a><br /> {/if}
            {if $plugindetails[0].pv_file_tgz}<a href="plugins/{$plugincomments[0].pv_file_tgz}/{$p.pluginversionid}/download">{$plugindetails[0].pv_file_tgz} </a>{/if}
            </td>
        </tr>
        <tr class="description">
            <td colspan="4">{if $plugincomments[0].pv_releasenotes}<span>Releasenotes: </span>{$plugincomments[0].pv_releasenotes}  {/if}<br />
            {if $plugincomments[0].tags}<span>Tags: </span> {foreach item=tag from=$plugincomments[0].tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach} {/if}</td>
            </tr>
        <tr>
            <td colspan="4">
            <a class="right" href="" onclick="showComments('{$plugindetails[0].pluginversionid}'); return false;">Write a Comment about this version</a>
            <br style="clear:both"; />
            </td>
        </tr>
    </table>
    <div id="comments-{$plugindetails[0].pluginversionid}" class="comment {if $plugindetails[0].pluginversionid ne $fields.pluginversionid || not $message}hidden{/if}">
        <h3>Write a Comment</h3>
        {if $message}<div id="message">{$message}</div>{/if}
        <form class="commentform" action="{$RELATIVE_URL}" method="post" name="commentform" enctype="multipart/form-data" onsubmit="return checkForm(this)">
            <label for="firstname">Firstname</label><input type="text" value="{if $fields.firstname}{$fields.firstname}{/if}" name="firstname" size="45" /><br />
            <label for="name">Name</label><input value="{if $fields.name}{$fields.name}{/if}" type="text" name="name" size="45" /> <br />
            <label for="email">Email (not for public visible)</label><input value="{if $fields.email}{$fields.email}{/if}" type="text" name="email" size="45" /><br />
            <input type="hidden"  value="{$plugindetails[0].pluginversionid}" name="pluginversionid"/>
            <label for="comment">Comment</label><br />
            <textarea cols="60" rows="5" name="comment">{if $fields.comment}{$fields.comment}{/if}</textarea> <br />
            <br /><button type="submit" name="btn_save" value="Save Comment &gt;&gt;" />Send Comment</button>
        </form>
    </div>
        {if $success}
        <div id="success">{$success}</div>
        {/if}
    {/if}
    {if $plugincomments}
        {foreach item=p from=$plugincomments}
        <div class="comments">
        <span>From: </span>{if $p.pc_name}{$p.pc_name} {/if} </span><br />
        <span>Date: </span>{if $p.date}{$p.date} {/if} <br />
        <span>Comment: </span>{$p.pc_comment} <br /> <br /></div>
        {/foreach}

{/if}
{/if}
