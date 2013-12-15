<?php

/**
 * PaymentInformation
 * The class PaymentInformation, which builts up a PaymentInformation block.
 * Each Payment Information block shall be added into an entity of class XMLPain.
 *
 * @package PHP-SEPA-Library
 * @version 1.0
 * @copyright 2013
 * @author Wouter van Groesen <w@flashin.nl>
 * @license The MIT License (MIT)
 */
class PaymentInformation {

    //<PmtInf> [1..0], PaymentInfo header 
    //PaymentInfo variables are:
    // - 2.1 <PmtInfId>, PaymentInformationIdentification
    // - 2.2 <PmtMtd>, PaymentMethod
    // - 2.3 <BtchBookg>, BatchBooking
    // - 2.4 <NbOfTxs>, NumberOfTransactions
    // - 2.5 <CtrlSum>, ControlSum
    // - 2.6 <PmtTpInf>, PaymentTypeInformation
    // - 2.18 <ReqdColltnDt>, RequestedCollectionDate
    // - 2.19 <Cdtr>, Creditor
    // - 2.20 <CdtrAcct>, CreditorAccount
    // - 2.21 <CdtrAgt>, CreditorAgent
    // - 2.22 <CdtrAgtAcct>, CreditorAgentAccount
    // - 2.23 <UltmtCdtr>, UltimateCredidor
    // - 2.24 <ChrgBr>, ChargeBearer
    // - 2.25 <ChrgsAcct>, ChargesAccount
    // - 2.26 <ChrgsAcctAgt>, ChargesAccountAgent
    // - 2.27 <CdtrSchmeId>, CreditorSchemeIdentification
    // - 2.28 <DrctDbtTxInf>, DirectDebitTransactionInformation

    //2.1 <PmtInfId> [1..1], PaymentInformationIdentification
    //Format: Text, Maxlenght: 35
    private var $_PmtInfId;

    //2.2 <PmtMtd> [1..1], PaymentMethod
    //Format: allways 'DD'
    private var $_PmtMtd         = 'DD';

    //2.3 <BtchBookg> [0..1], Batchbooking
    //Format: Either false or true
    // false: booking per transaction is requested
    // true : batch booking is requested
    private var $_BtchBookg      = 'false'; 

    //2.4 <NbOfTxs> [0..1], NumberOfTransactions
    //Format: Max15NumericText
    private var $_PmtInfNbOfTxs  = 0; 

    //2.5 <CtrlSum> [0..1], ControlSum
    //Format: DecimalNuber, fractionDigits:17 totalDigits: 18
    private var $_PmtInfCtrlSum  = 0; 

    //2.6 <PmtTpInf> [0..1], PaymentTypeInformation 
    //PaymentTypeInformation variables are:
    // - <InstrPrty>, Instruction priority
    // - <SvcLvl> , Service level
    // - <LclInstrum>, LocalInstrument
    // - <SeqTp>, Sequencetype
    // - <CtgyPurp>, CategoryPurpose

    //2.7 <InstrPrty>
    // Not used

    //2.8 <SvcLvl> [0..1], 2.9 <Cd> [1..1]
    //Service Level Code
    //Format: allways 'SEPA'
    private var $_SvcLvlCd       = 'SEPA';

    //2.11 <LclInstrm> [0..1], 2.12 <Cd> [1..1]
    //Service Level Code
    //Format: allways 'CORE'
    private var $_LclInstrmCd    = 'CORE';    

    //2.14 <SeqTp> [0..1]
    //Must contain one of the values "FRST", "RCUR", "FNAL" or "OOFF"
    private var $_SeqTp          = 'FRST';

    //2.15 <CtgyPurp>
    // Not used

    //2.18 <ReqdColltnDt> [1..1], RequestedCollectionDate
    //Format: date
    private var $_ReqdColltnDt;

    //2.19 <Cdtr> [1..1], Creditor
    //Creditor variables are:
    // - <Nm>, Name
    // - <PstlAdr>, PostalAddress
    // - <Id>, Identification
    // - <CtryOfRes>, CountryOfResidence 
    // - <CtctDtls>, ContactDetails


    //2.19 <Cdtr> [1..1], <Nm> [0..1], CreditorName 
    //Format: Text, MaxLength 70
    private var $_CdtrNm;

    //2.19 <Cdtr> [1..1], <PstlAdr> [0..1]
    //Creditor PostalAddress
    //Format: Text
    private var $_CdtrPstlAdr1;
    private var $_CdtrPstlAdr2;

    //2.19 <Cdtr> [1..1], <Id> [0..1]
    //Not Used

    //2.19 <Cdtr> [1..1], <CtryOfRes> [0..1]
    //Creditor CountryOfResidence
    //Format: Code
    private var $_CdtrCtryOfRes ='NL';

    //2.19 <Cdtr> [1..1], <CtctDtls> [0..1]
    //Not Used

    //2.20 <CdtrAcct> [1..1], <Id> [1..1]
    //CreditorAccount Identification
    //Format: IBAN, Sparta IBAN number
    private var $_CdtrIBAN       = 'NL12RABO0345678912';

    //2.21 <CdtrAgt> [1..1], <FinInstnId> [1..1]
    //CreditorAgent, FinancialInstitutionIdentification
    //Format: BIC, Sparta Rabobank BIC code
    private var $_CdtrBIC        = 'RABONL2U';  

    //2.22 <CdtrAgtAcct>, [0..1] CreditorAgentAccount
    //Not Used

    //2.23 <UltmtCdtr>, [0..1], UltimateCredidor
    //Not Used

    //2.24 <ChrgBr>, [0..1], ChargeBearer
    //Not Used

    //2.25 <ChrgsAcct>, [0..1], ChargesAccount
    //Not Used

    //2.26 <ChrgsAcctAgt>, [0..1], ChargesAccountAgent
    //Not Used

    //2.27 <CdtrSchmeId>, CreditorSchemeIdentification
    //CreditorSchemeIdentification variables are:
    // - <Nm>, Name
    // - <PstlAdr>, PostalAddress
    // - <Id>, Identification (Here mandatory for EPC)
    // - <CtryOfRes>, CountryOfResidence 
    // - <CtctDtls>, ContactDetails 

    //2.27 <CdtrScheId>, <Nm>
    //Used is $_CdtrNm;

    //2.27 <CdtrScheId>, <PstlAdr>
    //Not Used

    //2.27 <CdtrScheId>, <Id>, <PrvtId>, <Othr>, <Id> 
    //Definition: Number assigned by an agent to identify its customer.
    //Data Type: Max35Text
    //Format: maxLength: 35
    //minLength: 1
    private var $_CstmrNb        = 'CustomerNumber';

    //2.27 <CdtrScheId>, <Id>, <PrvtId>, <Othr>, <SchmeNm>, <Prtry>
    //EPC: 'Scheme Name'under 'Other' must specify 'SEPA' under 'Proprietary'
    //Value always 'SEPA'
    private var $_CdtrSchmeIdPrtry        = 'SEPA';

    // - 2.28 <DrctDbtTxInf>, DirectDebitTransactionInformation
    //See class DirectDebitTransactionInformation

    //placeholder for DirectDebitTransactionInformation
    private var $XMLDirectDebitInformationText ='';

    //define a standard RequestedCollectionDate over five days    
    public function __construct() {
        $TodaysDate           = date('Y-m-d' , time()); 
        $TodaysTime           = date('H:i:s' , time());
        $TimeStamp            = $TodaysDate."T".$TodaysTime; //Format 2012-05-12T15:03:34 according guideline
        $DateOverFiveDaysInt  = mktime(0, 0, 0, date("m")  , date("d")+5, date("Y"));
        $DateOverFiveDays     = date('Y-m-d' , $DateOverFiveDaysInt);

        $this->_ReqdColltnDt  = $DateOverFiveDays;
    }

    // property <PmtInfId>
    public function setPmtInfId($Value) {
        $this->_PmtInfId = substr($Value,0,34);
    }

    // property <BtchBookg>
    public function setBtchBookg($Value) {
        $this->_BtchBookg = $Value;       
    }

    // property <PmtInf NbOfTxs>
    public function getPmtInfNbOfTxs() {
        return $this->_PmtInfNbOfTxs;       
    }

    // property <PmtInf InfCtrlSum>
    public function getPmtInfCtrlSum() {
        return $this->_PmtInfCtrlSum;       
    }

    // property <ReqdColltnDt>,  must be according format 2012-05-12T15:03:34
    public function setReqdColltnDt($Value) {
        $this->_ReqdColltnDt = $Value;       
    }

    // property <CdtrNm>
    public function setCdtrNm($Value) {
        $this->_CdtrNm = substr($Value,0,69);       
    }

    // property <PstlAdr1>
    public function setCdtrPstlAdr1($Value) {
        $this->_CdtrPstlAdr1 = $Value;       
    }

    // property <PstlAdr2>
    public function setCdtrPstlAdr2($Value) {
        $this->_CdtrPstlAdr2 = $Value;       
    }

    // property <CdtrCtryOfRes>
    public function setCdtrCtryOfRes($Value) {
        $this->_CdtrCtryOfRes = $Value;       
    }

    // property <CdtrIBAN>
    public function setCdtrIBAN($Value) {
        $this->_CdtrIBAN = $Value;       
    }

    // property <CdtrBIC>
    public function setCdtrBIC($Value) {
        $this->_CdtrBIC = $Value;       
    }

    // property <CstmrNb>
    public function setCstmrNb($Value) {
        $this->_CstmrNb = $Value;       
    }

    //Adds a DirectDebitTransactionInformation block in the placeholder XMLDirectDebitInformationText.
    //Updates the GroupHeaderControlSum and the GroupHeaderNumberOfTransactions
    public function addDrctDbtTxInf($XMLDirectDebitInformationText,$InstdAmt){
        $this->XMLDirectDebitInformationText .=  $XMLDirectDebitInformationText;
        $this->_PmtInfCtrlSum += $InstdAmt; 
        $this->_PmtInfNbOfTxs += 1;
    }

    //return the XML text block for <DrctDbtTxInf> from the placeholder XMLDirectDebitInformationText
    public function getXMLDrctDbtTxInf(){
        return $this->XMLDirectDebitInformationText;
    }

    //create and return the XML PaymentInfoHeader 
    public function getXMLPaymentInfoHeader(){
        $XMLPaymentInfoHeader = <<<XMLPAYMENTINFOHEADER
            <PmtInf>
                <PmtInfId>{$this->_PmtInfId}</PmtInfId>
                <PmtMtd>{$this->_PmtMtd}</PmtMtd>
                <BtchBookg>{$this->_BtchBookg}</BtchBookg>
                <NbOfTxs>{$this->_PmtInfNbOfTxs}</NbOfTxs>
                <CtrlSum>{$this->_PmtInfCtrlSum}</CtrlSum>
                <PmtTpInf>
                    <SvcLvl>
                        <Cd>{$this->_SvcLvlCd}</Cd>
                    </SvcLvl>
                    <LclInstrm>
                        <Cd>{$this->_LclInstrmCd}</Cd>
                    </LclInstrm>
                    <SeqTp>{$this->_SeqTp}</SeqTp>
                </PmtTpInf>
                <ReqdColltnDt>{$this->_ReqdColltnDt}</ReqdColltnDt>
                <Cdtr>
                    <Nm>{$this->_CdtrNm}</Nm>
                    <PstlAdr>
                        <Ctry>{$this->_CdtrCtryOfRes}</Ctry>
                        <AdrLine>{$this->_CdtrPstlAdr1}</AdrLine>
                        <AdrLine>{$this->_CdtrPstlAdr2}</AdrLine>
                    </PstlAdr>
                </Cdtr>
                <CdtrAcct>
                    <Id>
                        <IBAN>{$this->_CdtrIBAN}</IBAN>
                    </Id>
                </CdtrAcct>
                <CdtrAgt>
                    <FinInstnId>
                        <BIC>{$this->_CdtrBIC}</BIC>
                    </FinInstnId>
                </CdtrAgt>
                <CdtrSchmeId>
                    <Id>
                        <PrvtId>
                            <Othr>
                                <Id>{$this->_CstmrNb}</Id>
                                <SchmeNm>
                                    <Prtry>{$this->_CdtrSchmeIdPrtry}</Prtry>
                                </SchmeNm>
                            </Othr>
                        </PrvtId>
                    </Id>
                </CdtrSchmeId>
XMLPAYMENTINFOHEADER;

        return $XMLPaymentInfoHeader;
    }

    public function getXMLPaymentInfoFooter() {
        return "            </PmtInf>\r\n";
    }

    //create and return the complete XMLPaymentInfo block
    public function getXMLPaymentInfo(){
        $XMLPaymentInfoText       = $this->getXMLPaymentInfoHeader();
        $XMLPaymentInfoText      .= $this->getXMLDrctDbtTxInf();
        $XMLPaymentInfoText      .= $this->getXMLPaymentInfoFooter();

        return $XMLPaymentInfoText;
    }
}
