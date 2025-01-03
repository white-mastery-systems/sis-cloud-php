<?php

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=helloworld.doc");
header('Content-Type: image/jpg');

?>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
<!--[if gte mso 9]-->
    <xml>
       <w:WordDocument>
             <w:View>Print</w:View>
        <w:Zoom>90</w:Zoom>
          <w:DoNotOptimizeForBrowser/>
     </w:WordDocument>
 </xml>
<!-- [endif]-->
    <style>
            p.MsoHeader, li.MsoHeader, div.MsoHeader{
                margin:0in;
                margin-top:.0001pt;
                mso-pagination:widow-orphan;
                tab-stops:center 3.0in right 6.0in;
            }
            p.MsoFooter, li.MsoFooter, div.MsoFooter{
                margin:0in;
                margin-bottom:.0001pt;
                mso-pagination:widow-orphan;
                tab-stops:center 3.0in right 6.0in;
            }
            @page Section1{
                size:8.5in 11.0in; 
				 
				  font-family:cambria;
				font-size:12px !important;
                margin:0.5in 0.5in 0.5in 0.5in;
                mso-header-margin:0.5in;
                mso-header:h1;
                mso-footer:f1; 
                mso-footer-margin:0.5in;
                mso-paper-source:0;
				 
				
				
            }
            div.Section1{
                page:Section1;
				
			
            }
			 p.MsoNormal, li.MsoNormal, div.MsoNormal
    {margin:0cm;
    margin-bottom:.0001pt;
    font-size:12.0pt;
    font-family:'cambria';}
 div.leftcolumn{
                position:relative;
				float:left;
				width:45%;
            }
			 div.center{
                position:relative;
				float:left;
				width:10%
            }
			div.main
			{
				width:100%;
			}
 div.rightcolumn{
                position:relative;
				float:left;
				width:45%
            }
            table#hrdftrtbl{
                margin:0in 0in 0in 9in;
            }  
			
        </style>
   
<body>
 <!-- Content -->

            <div class="Section1"><!--Section1 div starts-->

<v:shape id="_x0000_s1030" type="#_x0000_t75" style='position:absolute;
 left:0;text-align:left;margin-left:0;margin-top:17.95pt;width:7in;height:116.85pt;
 z-index:2;mso-position-horizontal:center;mso-position-horizontal-relative:page;
 mso-position-vertical-relative:page'>
 <v:imagedata src="http://cloud.sis.in/images/footerp.jpg" o:title="image001"/>
 <w:wrap anchorx="page" anchory="page"/>
 <w:anchorlock/>
</v:shape><![endif]--><![if !vml]><span style='mso-ignore:vglayout;position:
absolute;z-index:0;left:0px;margin-left:0px;margin-top:24px;width:672px;
height:156px'><img width=672 height=156
src="http://cloud.sis.in/images/footerp.jpg" v:shapes="_x0000_s1031"></span><![endif]>
<v:shape id="_x0000_s1031" type="#_x0000_t75" style='position:absolute;
 left:0;text-align:left;margin-left:0;margin-top:17.95pt;width:126px;
 z-index:2;mso-position-horizontal:center;mso-position-horizontal-relative:page;
 mso-position-vertical-relative:page'>
 <v:imagedata src="http://cloud.sis.in/images/footerp.jpg" o:title="image001"/>
 <w:wrap anchorx="page" anchory="page"/>
 <w:anchorlock/>
</v:shape><![endif]--><![if !vml]><span style='mso-ignore:vglayout;position:
absolute;z-index:0;left:0px;margin-left:0px;margin-top:24px;width:672px;
height:156px'><img width=672 height=156
src="http://cloud.sis.in/images/head.jpg" v:shapes="_x0000_s1031"></span><![endif]>

                <br/>

 

                <br clear="all" style="page-break-before:always" />

                <br/>

<br clear="all" style="page-break-before:always" />
    <br/>

                Page 3 Content

                 
        <!--Header and Footer Starts-->

                <table id='hrdftrtbl' border='1' cellspacing='0' cellpadding='0'>

                    <tr>

                        <td>

                            <!--Header-->

                            <div style='mso-element:header' id="h1" >

                                <p class="MsoHeader">

                                    <table border="1" width="100%">

                                        <tr>

                                            <td width="100%">

                                                <img src='https://www.google.co.in/search?q=hello&biw=1366&bih=634&source=lnms&tbm=isch&sa=X&ved=0ahUKEwiTlKncxZTKAhVJkI4KHYEhB6MQ_AUICCgD#imgrc=lFJETR2XYMqsQM%3A' alt='header' />

                                            </td>


                                        </tr>

                                    </table>

                                </p>

                            </div>

                        </td>

 

                        <td>

                            <!--Footer-->
 
                   <div style='mso-element:footer' id="f1">
                            <p class="MsoFooter">

                                    <table width="100%" border="1" cellspacing="0" cellpadding="0">

                                        <tr>

                                            <td width="100%">

                                          <img src='http://cloud.sis.in/images/footerp.jpg' alt='footer' />
                                   </td>

                                            

                                        </tr>

                                    </table>

                                </p>

                            </div>
                    </td>

                    </tr>

                </table>
      </div>
</body>
</html>
