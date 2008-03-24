{if $message}
<div class="message">{$message}</div>
{/if}

{if $userplugins}
You already have submitted jojo plugins.
If you click on one plugin, you can upload a new version or change the plugindetails
<form method="post" name="plugin-upload" action="plugins/plugin-upload/">
<select size="1" name="select-plugin">
{foreach item=p from=$userplugins}
 <option {if $plugindetails[0].pluginid eq $p.pluginid}selected{/if}  name="pluginid" value="{$p.pluginid}">{$p.pd_name}</option>
{/foreach}
</select>
<input type="submit" name="version" value="Upload Plugin Version" class="button" />
</form>
{/if}


<div class="upload">
{if $username}
<form method="post" enctype="multipart/form-data" name="plugin-upload" action="{if $pg_url}{$pg_url}{else}{$REQUEST_URI}{/if}/" onsubmit="return checkme()">
<div class="contact-form">
{if $version}
{if $plugindetails}
<h2>{if $plugindetails[0].pd_name}{$plugindetails[0].pd_name}{/if}</h2>
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
        <input type="hidden" name="pluginid" id="pluginid" value="{$plugindetails[0].pluginid}" />

    {/if}



{section name=f loop=$fields}
{if $fields[f].change eq  'true'}
{if $fields[f].comment}{$fields[f].comment} {/if}
<label for="form_{$fields[f].field}">{$fields[f].display}:</label>
{if $fields[f].type == 'textarea'}
<textarea rows="{$fields[f].rows|default:'10'}" cols="{$fields[f].cols|default:'40'}" name="form_{$fields[f].field}" id="form_{$fields[f].field}">{$fields[f].value}</textarea>
{elseif $fields[f].type == 'radio'}
<div class="form-field">
{section name=o loop=$fields[f].options}
<input type="radio" name="form_{$fields[f].field}" id="form_{$fields[f].field}_{$fields[f].options[o]}" value="{$fields[f].options[o]}" {if $fields[f].options[o] eq $fields[f].value}checked="checked"{/if}  /><label for="form_{$fields[f].field}_{$fields[f].options[o]}"> {$fields[f].options[o]}</label><br />
{/section}
</div>
{else}
<input type="{$fields[f].type}" size="{$fields[f].size | default:'30'}" name="form_{$fields[f].field}" id="form_{$fields[f].field}" value="{$fields[f].value}" />
{/if}
{if $fields[f].required} *{/if}<br />{/if}
{/section}


{else}
{section name=f loop=$fields}
{if $fields[f].comment}{$fields[f].comment} {/if}
<label for="form_{$fields[f].field}">{$fields[f].display}:</label>
{if $fields[f].type == 'textarea'}
<textarea rows="{$fields[f].rows|default:'10'}" cols="{$fields[f].cols|default:'40'}" name="form_{$fields[f].field}" id="form_{$fields[f].field}">{$fields[f].value}</textarea>
{elseif $fields[f].type == 'radio'}
<div class="form-field">
{section name=o loop=$fields[f].options}
<input type="radio" name="form_{$fields[f].field}" id="form_{$fields[f].field}_{$fields[f].options[o]}" value="{$fields[f].options[o]}" {if $fields[f].options[o] eq $fields[f].value}checked="checked"{/if}  /><label for="form_{$fields[f].field}_{$fields[f].options[o]}"> {$fields[f].options[o]}</label><br />
{/section}
</div>
{else}
<input type="{$fields[f].type}" size="{$fields[f].size | default:'30'}" name="form_{$fields[f].field}" id="form_{$fields[f].field}" value="{$fields[f].value}" />
{/if}
{if $fields[f].required} *{/if}<br />
{/section}
{/if}

<label>Submit Form:</label>
<input type="submit" name="submit" value="Submit" class="button" />

</div><br style="clear:style" />

</form>

You are logged in as {$username}. <a href="logout/"><img class="icon" src="images/cms/icons/status_offline.png" alt="" /></a> <a href="logout/">logout</a>  <a href="user-profile/"><img class="icon" src="images/cms/icons/user_edit.png" alt="" /></a> <a href="user-profile/">Edit Profile</a>

{else}<div id="login">
<form method="post" action="{if $issecure}{$SECUREEURL}{else}{$SITEURL}{/if}/{$RELATIVE_URL}">
<b>{$loginmessage|default:"You are not logged in."}</b> You can only upload a new plugin or pluginversion, if you are logged in with you username and password<br /><br />
Username: <input type="text" name="username" size="10" value="{$username}" />
<span class="login" title="Passwords are case-sensitive.">Password: <input type="password" size="10" name="password" value="{$password}" /></span>
<span class="login" title="This option will log you in automatically from this computer."><input type="checkbox" name="remember" id="remember" value="1" onclick="{literal}javascript: if (this.checked) {alert('Your login details will be remembered by this computer for up to 14 days, or until you login from another computer, or until you logout. Please do not use this option if you are on a shared computer.')};{/literal}" {if $remember=="1"} checked{/if} /> <label for="remember">Remember Me</label></span>

<input type="submit" class="button" name="submit" value="Login &gt;&gt;" class="button" onmouseover="this.className='button buttonrollover';" onmouseout="this.className='button'" /> <br />
<a href="forgot-password/" title="Options for recovering a lost password" rel="nofollow">Forgotten Password?</a>
</form></div>
{/if}
</div>

<script type="text/javascript">
<!-- //
function checkme()
{literal}{{/literal}
  var errors=new Array();
  var i=0;
{section name=f loop=$fields}

    {if $fields[f].required AND  $fields[f].change}
        {if $fields[f].type == 'radio'}
        {section name=o loop=$options}

        {/section}
        {else}
        if (document.getElementById('form_{$fields[f].field}').value == '') {literal}{{/literal}errors[i++]='{$fields[f].display} is a required field';{literal}}{/literal}
        {/if}
        {if $fields[f].validation=='email'} else if (!validateEmail(document.getElementById('form_{$fields[f].field}').value)) {literal}{{/literal}errors[i++]='{$fields[f].display} is not a valid email format';{literal}}{/literal}{/if}
    {/if}

{/section}

if (document.getElementById('form_file1').value == '' && document.getElementById('form_file2').value == '' && document.getElementById('form_file3').value == '')
{literal}{{/literal}errors[i++]='Please upload at least one archive file.';{literal}}{/literal}
{literal}
if (errors.length==0) {
    return(true);
  } else {
    alert(errors.join("\n"));
    return(false);
  }
}
{/literal}
//-->
</script>

