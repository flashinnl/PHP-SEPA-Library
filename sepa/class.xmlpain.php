<?php

/**
 * XMLPain
 * The main class XMLPain, which defines the boundaries of the XML file and contains the Group header block.
 *
 * @package PHP-SEPA-Library
 * @version 1.0
 * @copyright 2013
 * @author Wouter van Groesen <w@flashin.nl>
 * @license The MIT License (MIT)
 */
class XMLPain {

    //1.0 <GrpHdr>, [1..1], Group Header   
    //Group Header variables are:
    // - 1.1 <Msgid>, Messageidentification
    // - 1.2 <CreDtTm>, CreationDateTime
    // - 1.3 <Authstn>, Authorisation
    // - 1.6 <NbOfTxs>, NumberOfTransactions
    // - 1.7 <CtrlSum>, ControlSum
    // - 1.8 <InitPty>, InitiatingParty
    // - 1.9 <FwdgAgt>, ForwardingAgent

    //1.1 <MsgId> [1..1], MessageIdentification
    //Format: Text, MaxLength: 35, MinLenght: 1
    private $_MsgId;

    //1.2 <CreDtTm> [1..1], CreationDateTime
    //Format: ISODateTime e.g.: 2012-05-12T15:03:34; 
    private $_CreDtTm;     

    //1.3 <Authstn>, Authorisation 
    //Not used    

    //1.6 <NbOfTxs> [1..1], NumberOfTransactions
    //Format Max15NumericText
    private $_GrpHdrNbOfTxs = 0;

    //1.7 <CtrlSum> [0..1], ControlSum
    //Total of all individual amounts included in the message
    //Format: DecimalNumber, fractionDigits: 17 totalDigits: 18
    private $_GrpHdrCtrlSum = 0;

    //1.8 <InitgPty> [1..1], InititatingParty
    //Inititating Party variables are:
    // - <Nm>, Name
    // - <PstlAdr>, PostalAddress
    // - <Id>, Identification
    // - <CtryOfRes>, CountryOfResidence
    // - <CtctDtls>, ContactDetails

    //<Nm> [0..1], Name
    //Format: Text, MaxLength 70
    private $_Nm;

    //<PstlAdr>[0..1], PostalAddress
    //Not used

    //<Id> [0..1], Identification
    //Format: OrganisationIdentification: Either ‘BIC or BEI’ or one occurrence of ‘Other’ is allowed
    //Format: PrivateIdentification: Either ‘Date and Place of Birth’ or one occurrence of ‘Other’ is allowed.
    //Not used

    //<CtryOfRes> [0..1], CountryOfResidence
    //Format: Code
    //Not used

    //<CtctDtls> [0..1], ContactDetails
    //Not used 

    //1.9 <FwdgAgt>, ForwardingAgent    
    //Not used

    //placeholder for PaymentInformation Block
    private $XMLPaymentInformationText ='';

    // built the properties <MsgId> and <CreDtTm >
    public function __construct() {
        $TodaysDate           = date('Y-m-d' , time()); 
        $TodaysTime           = date('H:i:s' , time());
        $TimeStamp            = $TodaysDate."T".$TodaysTime; //Format 2012-05-12T15:03:34 according guideline
        $DateOverFiveDaysInt  = mktime(0, 0, 0, date("m")  , date("d")+5, date("Y"));
        $DateOverFiveDays     = date('Y-m-d' , $DateOverFiveDaysInt);

        //set default values if not changed by a set-function later
        $this->_MsgId         = substr("DirDebBatch ".$TimeStamp,0,34);
        $this->_CreDtTm       = $TimeStamp;
    }

    // property <MsgId>
    public function setMsgId($Value) {
        $this->_MsgId = substr($Value,0,34);       
    }

    // property <CreDtTm>
    public function getCreDtTm() {
        return $this->_CreDtTm;       
    }

    // property <Nm>
    public function setNm($Value) {
        $this->_Nm = substr($Value,0,69);       
    }

    //add a PaymentInformation block to the XML
    //update the GroupHeaderControlSum and the GroupHeaderNumberOfTransactions
    public function addPaymentInformationBlock($XMLPaymentInformationText,$PmtInfNbOfTxs,$PmtInfCtrlSum){
        $this->XMLPaymentInformationText .=  $XMLPaymentInformationText;
        $this->_GrpHdrCtrlSum += $PmtInfCtrlSum; 
        $this->_GrpHdrNbOfTxs += $PmtInfNbOfTxs;
    }

    //return the XML text for the <PmtInf> block
    public function getXMLPaymentInformation(){
        return $this->XMLPaymentInformationText;
    }

    //create and return the XML Header
    public function getXMLHeader() {
        $XMLheader = <<<XMLHEADER
<?xml version="1.0" encoding="UTF-8"?>
<Document xsi:schemaLocation="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02 pain.008.001.02.xsd" xmlns="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <CstmrDrctDbtInitn>
XMLHEADER;

        return $XMLheader;
    }

    //create and return the XML GroupHeader
    //deleted Grpg XML-tag
    public function getXMLGroupHeader(){
        $XMLgroupheader = <<<XMLGROUPHEADER
        <GrpHdr>
            <MsgId>{$this->_MsgId}</MsgId>
            <CreDtTm>{$this->_CreDtTm}</CreDtTm>
            <NbOfTxs>{$this->_GrpHdrNbOfTxs}</NbOfTxs>
            <CtrlSum>{$this->_GrpHdrCtrlSum}</CtrlSum>
            <InitgPty>
                <Nm>{$this->_Nm}</Nm>
            </InitgPty>
        </GrpHdr>
XMLGROUPHEADER;
        return $XMLgroupheader;
    }


    //create and return the XML Footer
    public function getXMLFooter(){
        $XMLfooter = <<<XMLFOOTER
    </CstmrDrctDbtInitn>
</Document>
XMLFOOTER;

        return $XMLfooter;
    }

    //compile, validate and return the XML file text
    public function getXMLText(){
        $XMLText  = $this->getXMLHeader();
        $XMLText .= $this->getXMLGroupHeader();
        $XMLText .= $this->getXMLPaymentInformation();
        $XMLText .= $this->getXMLFooter();

        // Only below characters can be used within the XML tags according the guideline.                
        // a b c d e f g h i j k l m n o p q r s t u v w x y z
        // A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
        // 0 1 2 3 4 5 6 7 8 9
        // / - ? : ( ) . , ‘ +
        // Space
        //
        // Create a normalized array and cleanup the string $XMLText for unexpected characters in names
        $normalizeChars = array(
            'Á'=>'A', 'À'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Å'=>'A', 'Ä'=>'A', 'Æ'=>'AE', 'Ç'=>'C',
            'É'=>'E', 'È'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Í'=>'I', 'Ì'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ð'=>'Eth',
            'Ñ'=>'N', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',
            'Ú'=>'U', 'Ù'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',

            'á'=>'a', 'à'=>'a', 'â'=>'a', 'ã'=>'a', 'å'=>'a', 'ä'=>'a', 'æ'=>'ae', 'ç'=>'c',
            'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e', 'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'eth',
            'ñ'=>'n', 'ó'=>'o', 'ò'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o',
            'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y',

            'ß'=>'sz', 'þ'=>'thorn', 'ÿ'=>'y',

            '&'=>'en', '@'=>'at', '#'=>'h', '$'=>'s', '%'=>'perc', '^'=>'-','*'=>'-'
        );

        $XMLText = strtr($XMLText, $normalizeChars);

        return $XMLText;
    }
}

