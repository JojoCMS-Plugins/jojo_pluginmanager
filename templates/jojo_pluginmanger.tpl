
{if $action eq 'all'  OR $action eq 'tags'  }
<div id="tabsJ">
	<ul>
	<li><a href="plugins/name"><span>Name</span></a></li>
	<li><a href="plugins/updated"><span>Last Updated</span></a></li>
	<li><a href="plugins/rating"><span>Rating</span></a></li>
	<li><a href="plugins/download"><span>Download</span></a></li>
	<li><a href="plugins/status"><span>Status</span></a></li>
	</ul>
</div>
<br style="clear:both;">
<div id="plugin">
	<table>
		<tr>
		<th>Name</th>
		<th>Last Updated</th>
		<th>Rating</th>
		<th>Download</th>
		</tr>
		{foreach item=p from=$plugins}
		<tr>
		<td><a href="plugins/{$p.pluginid}/details">{if $p.pd_name} {$p.pd_name} {/if}</a><br />{if $p.pv_status}{$p.pv_status} Version {/if}</td>
		<td>{if $p.date} {$p.date}  {/if}</td>
		<td class="stars">{if $p.stars}{$p.stars} {/if}</td>
		<td>{if $p.downloads}{$p.downloads} {/if}<br />
		<a href="plugins/{if $p.file}{$p.file}{/if}/{if $p.pluginversionid}{$p.pluginversionid}{/if}/download">{if $p.file}{$p.file} {/if}</a></td>
		</tr>

		<tr class="description">	
		<td colspan="5">{if $p.pd_description}<span>Description: </span>{$p.pd_description}  {/if}<br />
		{if $p.tags}<span>Tags: </span> {foreach item=tag from=$p.tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach} {/if}</td>
		</tr>
		{/foreach}
	</table>
</div>
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

{elseif $action eq 'details'}
	{if $plugindetails}
	<div id="plugindetails">
		<h2>{$plugindetails[0].pd_name}</h2>
		{if $plugindetails[0].pd_description}<p>{$plugindetails[0].pd_description}  {/if}</p>
		<div class="general">
		{if $plugindetails[0].pd_author}<span>Author: </span>{$plugindetails[0].pd_author}  {/if}<br />
		{if $plugindetails[0].pd_website}<span>Website: </span>{$plugindetails[0].pd_website}  {/if}<br />
		{if $plugindetails[0].pd_demolink}<span>Demolink: </span>{$plugindetails[0].pd_demolink}  {/if}<br />
		{if $plugindetails[0].pd_license}<span>license: </span>{$plugindetails[0].pd_license}  {/if}<br />
		{if $p.tags}<span>Tags: </span> {foreach item=tag from=$p.tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach}	{/if}
		{if $plugindetails[0].pd_tags}{$plugindetails[0].pd_tags}{/if}<br />
		</div>
		<br style="clear:both"; />
	</div>
	{/if}
	
	{foreach item=p from=$pluginversions}
	<br style="clear:both"; />
	<div class="versiondetails">
	<div>
	{if $p.pv_version}<span>Version: </span>{$p.pv_version} {/if}<br />
	{if $p.pv_status}<span>Status: </span>{$p.pv_status}  {/if}
	<br />{if $p.pv_file}<a href="plugins/{$p.pv_file}/{$p.pluginversionid}/download" title="Download {$plugindetails[0].pd_name}" alt="Download {$plugindetails[0].pd_name}"> {$p.pv_file} {/if} </a> 
	</div>
	<div>{if $p.stars}{$p.stars} {/if}</div>
	<div class="releasenotes">
	{if $p.pv_releasenotes}<span>Releasenotes: </span>{$p.pv_releasenotes} {/if} </div>
	{if $p.countComments gt  1}<div class="releasenotes"><span>Last Comment: </span>{$p.pc_comment|truncate:250}{else}{$p.pc_comment} {/if}
	{if $p.countComments gt  1}<div id="page"><a href="plugins/{$p.pluginversionid}/showComments" >Read all Comments</a></div>{/if}</div>
	</div>
	{/foreach}


{elseif $action eq 'allComments'}
	{if $plugindetails}
<div id="plugindetails">
	<h2>{$plugindetails[0].pd_name}</h2>
	<p>{if $plugindetails[0].pd_description}{$plugindetails[0].pd_description}  {/if}</p>
	<div class="general">
	{if $plugindetails[0].pd_author}<span>Author: </span>{$plugindetails[0].pd_author}  {/if}<br />
	{if $plugindetails[0].pd_website}<span>Website: </span>{$plugindetails[0].pd_website}  {/if}<br />
	{if $plugindetails[0].pd_license}<span>Demolink: </span>{$plugindetails[0].pd_demolink}  {/if}<br />
	{if $plugindetails[0].pd_license}<span>license: </span>{$plugindetails[0].pd_license}  {/if}<br />
	{if $$plugindetails[0].tags}<span>Tags: </span>
	{foreach item=tag from=$plugindetails[0].tags}
	<a href="plugins/{$tag}/tag">{$tag}</a>
	{/foreach}
	{/if}<br />
	</div><br style="clear:both"; />
</div>
{/if}
{if $plugincomments}

<br style="clear:both"; />
<div class="versiondetails">
	<div><span>Version: </span>{if $plugincomments[0].pv_version}{$plugincomments[0].pv_version} {/if}<br /><span>Status: </span>{if $plugincomments[0].pv_status}{$plugincomments[0].pv_status}  {/if}
	<br /><a href="plugins/{if $plugincomments[0].pv_file}{$plugincomments[0].pv_file}{/if}/{if $plugincomments[0].pluginversionid}{$plugincomments[0].pluginversionid}{/if}/download" title="Download {$plugincomments[0].pd_name}" alt="Download {$plugincomments[0].pd_name}"> {if $plugincomments[0].pv_file}{$plugincomments[0].pv_file} {/if} </a> </div>
	<div>{if $plugincomments[0].stars}{$plugincomments[0].stars} {/if}</div>
</div>

<div class="releasenotes"><span>Releasenotes: </span>{if $plugincomments[0].pv_releasenotes}{$plugincomments[0].pv_releasenotes} {/if} </div>


{foreach item=p from=$plugincomments}
<div class="releasenotes">
<span>Name: </span>{if $p.pc_name}{$p.pc_name} {/if}  <br /><span>Date: </span>{if $p.date}{$p.date} {/if} <br />
<span>Comment: </span>{$p.pc_comment} 
</div>


{/foreach}

{/if}

{/if}