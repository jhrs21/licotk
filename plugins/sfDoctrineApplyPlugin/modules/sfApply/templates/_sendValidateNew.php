<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
<p>Thanks for applying for an account with %1%.</p>

<p>
    To prevent abuse of the site, we require that you activate your
    account by clicking on the following link:
</p>

<p>%2%</p>

<p>Thanks again for joining us.</p>
EOM
, array("%1%" => $sf_request->getHost(),
  "%2%" => url_for("sfApply/confirm?validate=$validate", true))) ?>
