{if $configure['error']!=0}
<div class="error">
    {foreach $configure['error_name'] as $err}
              <img src="../img/admin/nav-logout.gif" alt="Confirmation" />{$err} <br/>
    {/foreach}
</div>
{/if}

{if $configure['settings_updated']==1}
<div class="conf confirm">
      <img src="../img/admin/ok.gif" alt="Confirmation" />
  Settings updated
</div>
{/if}

<html>
<head>
<link rel="stylesheet" type="text/css" href="{$module_template_dir}css/plugin-css.css">
</head>
<body>
<div class="all-content">
  <header class="header-wr">
    <div class="payzippy-logo-wr">
      <img src="{$module_template_dir}img/payzippy-logo.png" alt="PAYZIPPY"/>
    </div>
  </header>
  <div class="main-wr">
    <div class="head-wr">
      <h3 class="finally-head-txt">Finally, a payment gateway that gives you enough reasons to smile!</h3>
      <div class="more-head-txt">Rest of the head text goes here. Rest of the head text goes here. Rest of the head text goes here. Rest of the head text goes here.Rest of the head text goes here. Rest of the head text goes here.</div>
    </div>
    <div class="feature-list-wr clear-both">
      <ul class="feature-list clear-both">
        <li class="feat-li feat-li-first">
          <div class="feat-icons high-con"></div>
          <div class="feat-txt">
            <span>Higher</span><br><span>Conversions</span>
          </div>
        </li>
        <li class="feat-li">
          <div class="feat-icons secure"></div>
          <div class="feat-txt">100% Secure</div>
        </li>
        <li class="feat-li">
          <div class="feat-icons usr-exp-rd"></div>
          <div class="feat-txt">
            <span>User Experience</span><br><span>Redefined</span>
          </div>
        </li>
        <li class="feat-li feat-li-last">
          <div class="feat-icons grt-mer-sup"></div>
          <div class="feat-txt">
            <span>Great Merchant</span><br><span>Support</span>
          </div>
        </li>
      </ul>
    </div>
    <div class="know-more-wr">
      <a href="https://www.payzippy.com/merchants" class="know-more-txt">Know more</a>
    </div>
    <div class="supp-pay-wr">
      <div class="supp-pay-txt">
        Supported payment methods
      </div>
    </div>
    <div class="pay-mthds-wr">
      <div class="cr-db-wr clear-both">
        <div class="cr-db-txt">Credit / Debit Cards</div>
        <div class="cr-db-logos">
          <img src="{$module_template_dir}img/visa-icons.png" alt="VISA ICONS"/>
        </div>
      </div>
      <div class="net-bk-wr clear-both">
        <div class="net-bk-txt">Net Banking</div>
        <div class="net-bk-logos">
          <img src="{$module_template_dir}img/supported-banks.png" alt=""/>
        </div>
        <!-- <div class="more-txt-wr clear-both"><a href="javascript:" class="more-txt">more</a></div> -->
      </div>
    </div>
    <div class="help-wr">
      <div class="help-txt">
        <div class="need-help-txt">Need help?</div>
        <div class="reach-us-txt">Reach us at:</div>
        <div class="icon-img helpline-no"><a href='tel:1800-103-6006'>1800-103-6006</div>
        <div class="icon-img care-email"><a class="font-bold" href="mailto:care@payzippy.com">care@payzippy.com</a></div>
      </div>
    </div>
    <div class="enter-txt-wr clear-both">
      <div class="enter-txt">
        Enter the below credentials to get started
      </div>
    </div>
    <div class="cred-frm-wr">
      <form action="{$configure['URI']}" method="post" class="cred-frm">
        <div class="mid-wr pull-left"><label for="MID" class="frm-lbl">MID</label><input type="text" class="frm-elem" name="merchant_id" value="{$configure['merchant_id']}" /></div>
        <div class="keyid-wr pull-left"><label for="KeyID" class="frm-lbl">Key ID</label><input type="text" class="frm-elem" name="merchant_key_id" value="{$configure['merchant_key_id']}"/></div>
        <div class="apikey-wr pull-left"><label for="APIKey" class="frm-lbl">API Key</label><input type="text" class="frm-elem more-width" name="secretkey" value="{$configure['secret_key']}" autocomplete="off"/></div></br></br></br></br> 
        <label class="choose-lbl-txt">&nbsp;Choose the PayZippy label that you want to display in front end.</label>
      </br></br>

        <div class="pay_1-wr pull-left"><input type="radio" class="radio-btn-pay" name="payment_button" value="Paybutton-1" {if $configure['paybutton'] == 'Paybutton-1'} checked {/if} checked/><img src="{$module_template_dir}img/Paybutton-1.png" alt="PAYZIPPY" class="radio-btn-image" /></div>
        <div class="pay_2-wr pull-left"> <input type="radio" name="payment_button" value="Paybutton-2" {if $configure['paybutton'] == 'Paybutton-2'} checked {/if}/><img src="{$module_template_dir}img/Paybutton-2.png" alt="PAYZIPPY" class="radio-btn-image" /></div>
        <div class="pay_3-wr pull-left"> <input type="radio" name="payment_button" value="Paybutton-3" {if $configure['paybutton'] == 'Paybutton-3'} checked {/if}/><img src="{$module_template_dir}img/Paybutton-3.png" alt="PAYZIPPY" class="radio-btn-image" /></div>
        <div class="pay_4-wr pull-left"> <input type="radio" name="payment_button" value="Paybutton-4" {if $configure['paybutton'] == 'Paybutton-4'} checked {/if}/><img src="{$module_template_dir}img/Paybutton-4.png" alt="PAYZIPPY" class="radio-btn-image" /></div>
        <div class="pay_5-wr pull-left"> <input type="radio" name="payment_button" value="Paybutton-5" {if $configure['paybutton'] == 'Paybutton-5'} checked {/if}/><img src="{$module_template_dir}img/Paybutton-5.png" alt="PAYZIPPY" class="radio-btn-image" /></div></br></br></br></br></br>
        <label class="choose-lbl-ui">&nbsp;Choose your UI mode:&nbsp; </label>
        <div class="ui_1-wr" style="float:left;">
        <select name="ui_mode">
        <option value="REDIRECT" {if $configure['uimode'] == 'REDIRECT'}selected{/if}>REDIRECT</option>
        <option value="IFRAME" {if $configure['uimode'] == 'IFRAME'}selected{/if}>IFRAME</option>
        </select></div>
        <div class="sub-wr"><input type="submit" value="Submit" class="pay-btn" name="update_settings"  /></div>
        
      </form>
    </div>
    <!-- <div class="no-mid-wr">
      <div class="no-mid-txt">
       Don't have MID details? No worries  
      </div>
    </div>
    <div class="get-cred-wr">
      <a href="javascript:" class="get-cred-btn">Get credentials now</a>
    </div> 
  </div> 
  <footer class="footer clear-both">
    <div class="nav-links-wr">
      <ul class="nav-links-ul clear-both">
        <li class="nav-links-li"><a href="javascript:">Home</a></li>
        <li class="nav-links-li"><a href="javascript:">FAQs</a></li>
        <li class="nav-links-li"><a href="javascript:">Terms Of Use</a></li>
        <li class="nav-links-li li-last"><a href="javascript:">Privacy Policy</a></li>
      <ul>
    </div> -->
    <div class="copy-rt-wr clear-both">
      <div class="copy-rt-txt">
        Copyright &copy; 2014 Flipkart Payment Gateway Services Pvt. Ltd. All Rights Reserved.
      </div>
    </div>
  </footer>

  <!-- Code for overlay Enter details to get started-->
  <div class="overlay">
    <div class="overlay-bg">&nbsp;</div>
    <div class="overlay-out-wr">
      <span class="close-cross">&nbsp;</span>
      <div class="overlay-in-wr">
        <div class="enter-details-wr">
          <span class="enter-details-txt">Enter the details to get started</span>
        </div>
        <div class="details-frm-wr">
          <form class="details-frm">
            <div class="website-wr">
              <label for="website" class="frm-lbl">Website</label>
              <input type="text" class="frm-elem more-mar"/>
            </div>
            <div class="email-wr">
              <label for="Email" class="frm-lbl">Email</label>
              <input type="text" class="frm-elem more-mar"/>
            </div>
            <div class="mobile-wr">
              <label for="Mobile" class="frm-lbl">Mobile</label>
              <input type="text" class="frm-elem more-mar"/>
            </div>
            <div class="det-cred-wr">
              <a href="javascript:" class="details-cred-btn">Get credentials now</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- -->

</div>
</body>
</html>