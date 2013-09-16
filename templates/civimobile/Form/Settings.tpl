<h3>CiviMobile Settings</h3>

<table class="form-layout-compressed">
  <tr>
    <td colspan="2" class="description">Please select the profile to use for your various contact types below.</td><td></td>
  </tr>
{if isset($form.ind_profile_id)}
  <tr class="civimobile-form-block-ind_profile_id">
    <td class="label">{$form.ind_profile_id.label}</td>
    <td>{$form.ind_profile_id.html}</td>
  </tr> 
{/if}
{if isset($form.house_profile_id)}
  <tr class="civimobile-form-block-house_profile_id">
    <td class="label">{$form.house_profile_id.label}</td>
    <td>{$form.house_profile_id.html}</td>
  </tr> 
{/if}
{if isset($form.org_profile_id)}
  <tr class="civimobile-form-block-org_profile_id">
    <td class="label">{$form.org_profile_id.label}</td>
    <td>{$form.org_profile_id.html}</td>
  </tr> 
{/if}
</table>
 <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>

