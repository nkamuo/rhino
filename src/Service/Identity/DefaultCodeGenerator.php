<?php
namespace App\Service\Identity;


class DefaultCodeGenerator implements CodeGeneratorInterface{
    
	/**
	 * @param null|string $prefix
	 * @return string
	 */
	public function generateCode(?string $prefix = null, $retainCase = false): string {
        // return uniqid($prefix, true);
		$code =  base_convert(bin2hex(random_bytes(6)),16,36);
		if(!$retainCase)
			$code = strtoupper($code);

		return $code;
	}
}