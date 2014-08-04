<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
%TEASER%

%HEADER%

%BODY%

%FOOTER%
EOM
    , array(
'%TEASER%'  => $teaser,
'%HEADER%'  => include_partial('api2/emailsPartials/partials/textHeader'),
'%BODY%'    => $body,
'%FOOTER%'  => include_partial('api2/emailsPartials/partials/textHeader')
    )
)
?>