<?php

// function to sanitize input; file is included and used in all relevant files
function prepareInput($inputData){
	$inputData = trim($inputData);
	$inputData = htmlspecialchars($inputData);
	return $inputData;
}

?>
