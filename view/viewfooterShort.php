<?php

if ( (isset($_SESSION['lang'])) && ($_SESSION['lang']=='en')) {

echo'
<div class="footerShort">
    <ul>
    <li class="button"><a class="whitelink" href="index.php?action=cgu">GCU</a></li>
    <li class="button"><a class="whitelink" href="index.php?action=contactus">Contact us</a></li>
    <li class="button"><a class="whitelink" href="index.php?action=faq">FAQ</a></li>
    <li class="button"><a class="whitelink" href="index.php?action=forum">Forum</a></li>
    </ul>
 </div>';

} else {
echo'
<div class="footerShort">
    <ul>
    <li class="button"><a class="whitelink" href="index.php?action=cgu">CGU</a></li>
    <li class="button"><a class="whitelink" href="index.php?action=contactus">Nous contacter</a></li>
    <li class="button"><a class="whitelink" href="index.php?action=faq">FAQ</a></li>
    <li class="button"><a class="whitelink" href="index.php?action=forum">Forum</a></li>
    </ul>
 </div>';
}
 ?>