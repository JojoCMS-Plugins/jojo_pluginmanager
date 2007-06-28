<a href="plugins/plugin-upload/"><span>Upload a new plugin</span></a>


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

	<table class="detailsTable">
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
		{if $p.pv_file_zip}<a href="plugins/{$p.pv_file_zip}/{$p.pluginversionid}/download">{$p.pv_file_zip} </a><br />{/if}
		{if $p.pv_file_7z}<a href="plugins/{$p.pv_file_7z}/{$p.pluginversionid}/download">{$p.pv_file_7z}</a><br /> {/if}
		{if $p.pv_file_tgz}<a href="plugins/{$p.pv_file_tgz}/{$p.pluginversionid}/download">{$p.pv_file_tgz} </a>{/if}
		</td>
		</tr>

		<tr class="description">	
		<td colspan="5">{if $p.pd_description}<span>Description: </span>{$p.pd_description}  {/if}<br />
		{if $p.tags}<span>Tags: </span> {foreach item=tag from=$p.tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach} {/if}</td>
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

{elseif $action eq 'details'}

	{if $pluginversions}
		<div class="description">
		{if $pluginversions[0].pd_description}{$pluginversions[0].pd_description}{/if}
		</div>
		<div class="plugindetails">
		{if $pluginversions[0].pd_author}<span>Author: </span>{$pluginversions[0].pd_author}  {/if}<br />
		{if $pluginversions[0].pd_website}<span>Website: </span>{$pluginversions[0].pd_website}  {/if}<br />
		{if $pluginversions[0].pd_demolink}<span>Demolink: </span>{$pluginversions[0].pd_demolink}  {/if}<br />
		{if $pluginversions[0].pd_license}<span>license: </span>{$pluginversions[0].pd_license}  {/if}<br />
		{if $pluginversions[0].tags}<span>Tags: </span> {foreach item=tag from=$pluginversions[0].tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach}	{/if}
		</div>	
	{/if}

	
		{foreach item=p from=$pluginversions}		
		<table class="detailsTable">
			<caption>versionnumber: {$p.pv_version}</caption>
			<tr>
				<th>Status</th>
				<th>Last Updated</th>
				<th>Rating</th>
				<th>Download</th>
			</tr>
			<tr>
				<td>{if $p.pv_status}{$p.pv_status} Version {/if}</td>
				<td>{if $p.date} {$p.date}  {/if}</td>
				<td class="stars">{if $p.stars}{$p.stars} {/if}</td>
				<td>{if $p.pv_downloads}{$p.pv_downloads} {/if}<br />
				{if $p.pv_file_zip}<a href="plugins/{$p.pv_file_zip}/{$p.pluginversionid}/download">{$p.pv_file_zip} </a><br />{/if}
				{if $p.pv_file_7z}<a href="plugins/{$p.pv_file_7z}/{$p.pluginversionid}/download">{$p.pv_file_7z}</a><br /> {/if}
				{if $p.pv_file_tgz}<a href="plugins/{$p.pv_file_tgz}/{$p.pluginversionid}/download">{$p.pv_file_tgz} </a>{/if}
				</td>
			<tr class="description">	
				<td colspan="4">{if $p.pv_releasenotes}<span>Releasenotes: </span>{$p.pv_releasenotes}  {/if}<br />
				{if $p.tags}<span>Tags: </span> {foreach item=tag from=$p.tags} <a href="plugins/{$tag}/tag">{$tag}</a> {/foreach} {/if}</td>
			</tr>			
			{if $p.countComments}			
			<tr class="description">	
				<td colspan="4">
				<span>Last Comment </span><br />
				<span>From: </span>{if $p.pc_name}{$p.pc_name} {/if} </span><br />
				<span>Date: </span>{if $p.pc_date}{$p.pc_date} {/if} <br />
				<span>Comment: </span>{if $p.countComments gt  1}{$p.pc_comment|truncate:250}<br /> <br />		
				<a href="plugins/{$p.pluginversionid}/showComments" >Read all Comments</a><br style="clear:both"; />
				{else}{$p.pc_comment}
				</td>{/if}	
			</tr>
			{/if}
			<tr>
				<td colspan="4"><a href="" onclick="showComments('{$p.pluginversionid}'); return false;">Write a Comment about this version</a>
				<br style="clear:both"; /></td>	
			</tr>	
		</table>
			<div id="comments-{$p.pluginversionid}" class="comment {if $p.pluginversionid ne $fields.pluginversionid || not $message}hidden{/if}">
			<h3>Write a Comment</h3>
			{if $message}<div id="message">{$message}</div>{/if}
			<form class="commentform" action="{$RELATIVE_URL}" method="post" name="commentform" enctype="multipart/form-data" onsubmit="return checkForm(this)">
				<label for="firstname">Firstname</label><input type="text" value="{if $fields.firstname}{$fields.firstname}{/if}" name="firstname" size="45" /><br />
				<label for="name">Name</label><input value="{if $fields.name}{$fields.name}{/if}" type="text" name="name" size="45" /> <br />
				<label for="email">Email (not for public visible)</label><input value="{if $fields.email}{$fields.email}{/if}" type="text" name="email" size="45" /><br />
				<input type="hidden"  value="{$p.pluginversionid}" name="pluginversionid"/>
				<label for="comment">Comment</label><br />
				<textarea cols="60" rows="5" name="comment">{if $fields.comment}{$fields.comment}{/if}</textarea> <br />	
				{if $OPTIONS.contactcaptcha == 'yes'}
				<label for="CAPTCHA">Spam prevention:</label><br />
				<div class="form-field">		
				Please enter the 3 letter code below. This helps us prevent spam.<br />
				<img src="external/php-captcha/visual-captcha.php" width="200" height="60" alt="Visual CAPTCHA" /><br />
				<em>Code is not case-sensitive</em>
				<input type="text" size="8" name="CAPTCHA" id="CAPTCHA" value="" /><br />
				</div><br />
				{/if}
				<br /><button type="submit" name="btn_save" value="Send Comment &gt;&gt;" />Send Comment</button>
			</form>
		</div>	
		{if $success}
		<div id="success">{$success}</div>	
		{/if}	
	{/foreach}


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
			{if $OPTIONS.contactcaptcha == 'yes'}
			<label for="CAPTCHA">Spam prevention:</label><br />
			<div class="form-field">		
			Please enter the 3 letter code below. This helps us prevent spam.<br />
			<img src="external/php-captcha/visual-captcha.php" width="200" height="60" alt="Visual CAPTCHA" /><br />
			<em>Code is not case-sensitive</em>
			<input type="text" size="8" name="CAPTCHA" id="CAPTCHA" value="" /><br />
			</div><br />
			{/if}
			<br /><button type="submit" name="btn_save" value="Send Comment &gt;&gt;" />Send Comment</button>
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