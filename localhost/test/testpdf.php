<?php
require "fonctions.php";
// $content = "
ob_start();
?>

<page>
<h1>Exemple d'utilisation</h1>
<br>
Ceci est un <b>exemple d'utilisation</b>
de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
</page>

<?php
$content = ob_get_clean();

printpdf($content);

// require_once(dirname(__FILE__).'/html2pdf/vendor/autoload.php');
// try {
//   $html2pdf = new HTML2PDF('P','A4','fr');
//   $html2pdf->SetDefaultFont('Arial');
//   $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
//   $html2pdf->pdf->IncludeJS('print(true)'); // Devrait afficher les options d'impressions - ne semble pas fonctionner sur Mac
//   $html2pdf->pdf->SetDisplayMode('fullpage'); // Affichage d'une page entière
//   $html2pdf->Output('testpdf.pdf');
// }
// catch(HTML2PDF_exception $e) {
//   echo $e;
// }
?>
