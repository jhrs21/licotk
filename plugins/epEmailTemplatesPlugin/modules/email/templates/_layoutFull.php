<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <!-- Facebook sharing information tags -->
        <meta property="og:title" content="%EMAIL_SUBJECT%" />

        <title>%EMAIL_SUBJECT%</title>
        <style type="text/css">
            /* Client-specific Styles */
            #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
            body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
            body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

            /* Reset Styles */
            body{margin:0; padding:0;}
            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
            table td{border-collapse:collapse;}
            #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

            /* Template Styles */

            /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: COMMON PAGE ELEMENTS /\/\/\/\/\/\/\/\/\/\ */

            /**
            * @tab Page
            * @section background color
            * @tip Set the background color for your email. You may want to choose one that matches your company's branding.
            * @theme page
            */
            body, #backgroundTable{
                <?php include_partial('email/styles/layoutContainerStyle') ?>
            }

            /**
            * @tab Page
            * @section email border
            * @tip Set the border for your email.
            */
            #templateContainer{
                <?php include_partial('email/styles/templateContainerStyle') ?>
            }

            /**
            * @tab Page
            * @section heading 1
            * @tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
            * @style heading 1
            */
            h1, .h1{
                <?php include_partial('email/styles/h1Style') ?>
            }

            /**
            * @tab Page
            * @section heading 2
            * @tip Set the styling for all second-level headings in your emails.
            * @style heading 2
            */
            h2, .h2{
                <?php include_partial('email/styles/h2Style') ?>
            }

            /**
            * @tab Page
            * @section heading 3
            * @tip Set the styling for all third-level headings in your emails.
            * @style heading 3
            */
            h3, .h3{
                <?php include_partial('email/styles/h3Style') ?>
            }

            /**
            * @tab Page
            * @section heading 4
            * @tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
            * @style heading 4
            */
            h4, .h4{
                <?php include_partial('email/styles/h4Style') ?>
            }

            /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: PREHEADER /\/\/\/\/\/\/\/\/\/\ */

            /**
            * @tab Header
            * @section preheader style
            * @tip Set the background color for your email's preheader area.
            * @theme page
            */
            #templatePreheader{
                <?php include_partial('email/styles/preHeaderContainerStyle') ?>
            }

            /**
            * @tab Header
            * @section preheader text
            * @tip Set the styling for your email's preheader text. Choose a size and color that is easy to read.
            */
            .preheaderContent div{
                <?php include_partial('email/styles/preHeaderContentStyle') ?>
            }

            /**
            * @tab Header
            * @section preheader link
            * @tip Set the styling for your email's preheader links. Choose a color that helps them stand out from your text.
            */
            .preheaderContent div a:link, .preheaderContent div a:visited, /* Yahoo! Mail Override */ .preheaderContent div a .yshortcuts /* Yahoo! Mail Override */{
                <?php include_partial('email/styles/linksStyle') ?>
            }

            /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: HEADER /\/\/\/\/\/\/\/\/\/\ */

            /**
            * @tab Header
            * @section header style
            * @tip Set the background color and border for your email's header area.
            * @theme header
            */
            #templateHeader{
                <?php include_partial('email/styles/headerContainerStyle') ?>
            }

            /**
            * @tab Header
            * @section header text
            * @tip Set the styling for your email's header text. Choose a size and color that is easy to read.
            */
            .headerContent{
                <?php include_partial('email/styles/headerContentStyle') ?>
            }

            /**
            * @tab Header
            * @section header link
            * @tip Set the styling for your email's header links. Choose a color that helps them stand out from your text.
            */
            .headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
                <?php include_partial('email/styles/linksStyle') ?>
            }

            #headerImage{
                <?php include_partial('email/styles/headerImageStyle') ?>
            }

            /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: MAIN BODY /\/\/\/\/\/\/\/\/\/\ */

            /**
            * @tab Body
            * @section body style
            * @tip Set the background color for your email's body area.
            */
            .bodyContent{
                <?php include_partial('email/styles/bodyContainerStyle') ?>
            }

            /**
            * @tab Body
            * @section body text
            * @tip Set the styling for your email's main content text. Choose a size and color that is easy to read.
            * @theme main
            */
            .bodyContent div{
                <?php include_partial('email/styles/bodyContentStyle') ?>
            }

            /**
            * @tab Body
            * @section body link
            * @tip Set the styling for your email's main content links. Choose a color that helps them stand out from your text.
            */
            .bodyContent div a:link, .bodyContent div a:visited, /* Yahoo! Mail Override */ .bodyContent div a .yshortcuts /* Yahoo! Mail Override */{
                <?php include_partial('email/styles/linksStyle') ?>
            }

            /**
            * @tab Body
            * @section button style
            * @tip Set the styling for your email's button. Choose a style that draws attention.
            */
            .templateButton{
                <?php include_partial('email/styles/buttonContainerStyle') ?>
            }

            /**
            * @tab Body
            * @section button style
            * @tip Set the styling for your email's button. Choose a style that draws attention.
            */
            .templateButton, .templateButton a:link, .templateButton a:visited, /* Yahoo! Mail Override */ .templateButton a .yshortcuts /* Yahoo! Mail Override */{
                <?php include_partial('email/styles/buttonStyle') ?>
            }

            .bodyContent img{
                <?php include_partial('email/styles/bodyContentImageStyle') ?>
            }

            /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: FOOTER /\/\/\/\/\/\/\/\/\/\ */

            /**
            * @tab Footer
            * @section footer style
            * @tip Set the background color and top border for your email's footer area.
            * @theme footer
            */
            #templateFooter{
                <?php include_partial('email/styles/footerContainerStyle')?>
            }

            /**
            * @tab Footer
            * @section footer text
            * @tip Set the styling for your email's footer text. Choose a size and color that is easy to read.
            * @theme footer
            */
            .footerContent div{
                <?php include_partial('email/styles/footerContentStyle')?>
            }

            /**
            * @tab Footer
            * @section footer link
            * @tip Set the styling for your email's footer links. Choose a color that helps them stand out from your text.
            */
            .footerContent div a:link, .footerContent div a:visited, /* Yahoo! Mail Override */ .footerContent div a .yshortcuts /* Yahoo! Mail Override */{
                <?php include_partial('email/styles/linksStyle') ?>
            }

            .footerContent img{
                <?php include_partial('email/styles/footerContentImageStyle') ?>
            }

            /**
            * @tab Footer
            * @section utility bar style
            * @tip Set the background color and border for your email's footer utility bar.
            * @theme footer
            */
            #utility{
                <?php include_partial('email/styles/utilityContainerStyle') ?>
            }

            /**
            * @tab Footer
            * @section utility bar style
            * @tip Set the background color and border for your email's footer utility bar.
            */
            #utility div{
                <?php include_partial('email/styles/utilityContentStyle') ?>
            }
        </style>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center>
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="<?php include_partial('email/styles/layoutContainerStyle') ?>">
                <tr>
                    <td align="center" valign="top" style="padding-top:20px;">
                        <?php include_partial('email/partials/preHeader') ?>
                        <?php include_partial('email/partials/templateContainer') ?>
                        <br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>