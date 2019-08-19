<?php
namespace Decrypter;
use DOMDocument;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Key;
class Decrypter
{
    protected $xmlPath;
    protected $exportKey;
    protected $masterKey;
    public function __construct($xmlPath, $exportKey, $masterKey)
    {
        $this->xmlPath = $xmlPath;
        $this->exportKey = $exportKey;
        $this->masterKey = $masterKey;
    }
    public function decrypt()
    {
        $decryptedNodes = $this->decryptXmlNodes($this->xmlPath, $this->exportKey);
        $decryptedNodesValidXML = $this->decryptedNodesToValidXML($decryptedNodes);
        list($categories, $clients) = $this->getCategoriesAndClients($decryptedNodesValidXML);
        $decryptedAccounts = $this->decryptAccountPasswords(
            $decryptedNodesValidXML,
            $categories,
            $clients
        );
        return $decryptedAccounts;
    }
    protected function unlockKey($key, $password)
    {
        $unlockedKey = KeyProtectedByPassword::loadFromAsciiSafeString($key)
            ->unlockKey($password);
        return $unlockedKey;
    }
    protected function decryptXmlNodes($xml, $exportKey)
    {
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);
        $dataNodes = $xmlDoc->getElementsByTagName('Data');
        $decryptedNodes = [];
        foreach ($dataNodes as $dataNode) {
            $data = base64_decode($dataNode->nodeValue);
            
            $lockedKey = $dataNode->getAttribute('key');
            $unlockedKey = $this->unlockKey($lockedKey, $this->exportKey);
            $decryptedNode = Crypto::decrypt($data, $unlockedKey);
            $decryptedNodes[] = $decryptedNode;
        }
        return $decryptedNodes;
    }
    protected function decryptedNodesToValidXML($decryptedNodes)
    {
        $decryptedNodesValidXML = '<root>';
        foreach ($decryptedNodes as $decryptedNode) {
            $decryptedNodesValidXML .= $decryptedNode;
        }
        $decryptedNodesValidXML .= '</root>';
        file_put_contents('decrypted.xml', $decryptedNodesValidXML);
        return $decryptedNodesValidXML;
    }
    protected function getCategoriesAndClients($decryptedNodes)
    {
        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML($decryptedNodes, LIBXML_PARSEHUGE);
        $xmlDoc->saveXML();
        $nodeNames = ['Category', 'Client'];
        $categoriesAndClients = [];
        $outerNodeIndex = 0;
        foreach ($nodeNames as $nodeName) {
            $nodes = $xmlDoc->getElementsByTagName($nodeName);
            foreach ($nodes as $node) {
                $id = $node->getAttribute('id');
                $name = $node->getElementsByTagName('name')
                    ->item(0)
                    ->nodeValue;
                $categoriesAndClients[$outerNodeIndex][$id] = $name;
            }
            $outerNodeIndex++;
        }
        return $categoriesAndClients;
    }
    protected function decryptAccountPasswords($decryptedNodes, $categories, $clients)
    {
        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML($decryptedNodes, LIBXML_PARSEHUGE);
        $xmlDoc->saveXML();
        $accountNodes = $xmlDoc->getElementsByTagName('Account');
        $decryptedAccounts = [];
        foreach ($accountNodes as $accountNode) {
            $url = $accountNode->getElementsByTagName('url')
                ->item(0)
                ->nodeValue;
            $name = $accountNode->getElementsByTagName('name')
                ->item(0)
                ->nodeValue;
            $accountLogin = $accountNode->getElementsByTagName('login')
                ->item(0)
                ->nodeValue;
            $accountKey = $accountNode->getElementsByTagName('key')
                ->item(0)
                ->nodeValue;
            $accountPassword = $accountNode->getElementsByTagName('pass')
                ->item(0)
                ->nodeValue;
            $clientId = $accountNode->getElementsByTagName('clientId')
                ->item(0)
                ->nodeValue;
            $categoryId = $accountNode->getElementsByTagName('categoryId')
                ->item(0)
                ->nodeValue;
            $unlockedKey = $this->unlockKey($accountKey, $this->masterKey);
            $decryptedPassword = Crypto::decrypt($accountPassword, $unlockedKey);
            $encoding = mb_detect_encoding($decryptedPassword);
            if (strtolower($encoding) === 'utf-8') {
                $decryptedPassword = utf8_decode($decryptedPassword);
            }
            $decryptedAccounts[] = [
                'url' => $url,
                'name' => $name,
                'login' => $accountLogin,
                'password' => $decryptedPassword,
                'category' => $categories[$categoryId],
                'client' => $clients[$clientId]
            ];
        }
        return $decryptedAccounts;
    }
}
