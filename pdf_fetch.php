<?php
require_once('database.php');

$filename = $_GET['filename_save'];

//Path to the edited PDF file, to get the file, use file_get_contents(URL);

	$_GET['path_pdf_output'];
	$content = file_get_contents($_GET['path_pdf_output']);
?>

<?php
	$folder="uploads/" .$filename;

	file_put_contents( $folder, $content );
	chmod($folder, 0777);
	//URL to delete the PDF file from our server
    echo base64_decode($_GET['delete_link_base64']);

?>
		<script>
			alert('successfully commented/signed');
			window.close();
	        //window.location.href='index.php?success';
		</script>
