<?php

namespace Decrypter;

use DOMDocument;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Key;

class Decrypter
{
    public static function getDecryptedNodes($xmlPath, $exportKey)
    {
        $decryptedNodes = self::loadXml($xmlPath, $exportKey);

        return $decryptedNodes;
    }

    protected function unlockKey($key, $password)
    {
        $unlockedKey = KeyProtectedByPassword::loadFromAsciiSafeString($key)
            ->unlockKey($password);

        return $unlockedKey;
    }

    public function loadXml($xml, $exportKey)
    {
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);

        $dataNodes = $xmlDoc->getElementsByTagName('Data');
        $decryptedNodes = [];

        foreach ($dataNodes as $dataNode) {

            $data = base64_decode($dataNode->nodeValue);
            
            $lockedKey = $dataNode->getAttribute('key');
            $unlockedKey = self::unlockKey($lockedKey, $exportKey);

            $decryptedNodes[] = Crypto::decrypt($data, $unlockedKey);
        }

        return $decryptedNodes;
    }

}

