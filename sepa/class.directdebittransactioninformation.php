<?php

/**
 * DirectDebitTransactionInformation
 * The class DirectDebitTransactionInformation, which is a storage area for the
 * information about a single debitor. This Transaction Information block shall be
 * added for each debitor into an entity of a class PaymentInformation.
 *
 * @package PHP-SEPA-Library
 * @version 1.0
 * @copyright 2013
 * @author Wouter van Groesen <w@flashin.nl>
 * @license The MIT License (MIT)
 */
class DirectDebitTransactionInformation {
    //+----------------------------------------------------------------------+
    //| 2.28 XML <DrctDbtTxInf> DirectDebitTransactionInformation variables  |
    //+----------------------------------------------------------------------+  

    //2.29 <PmtId> [1..1], 2.31 <EndToEndId> [1..1]
    //PaymentIdentification, EndToEndIdentification
    //Format: Text, Maxlenght: 35
    private var $_EndToEndId;   

    //2.44 <InstdAmt Ccy="EUR">, [1..1], InstructedAmount
    //Format: Number, eg 12.34
    private var $_InstdAmt;  

    //iso 2.42 <MndtId>,[0..1], MandateIdentification, Text
    private var $_MndtId    = 'lidmaatschap per';

    //iso 2.43 <DtOfSgntr>, [0..1], DateOfSignature, Date
    private var $_DtOfSgntr = '2012-05-12';

    //2.70 <DbtrAgt> [1..1], <FinInstnId> [1..1], <BIC> [1..1]
    //DebtorAgent, FinancialInstitutionIdentification, BIC
    //Format: Debitor BIC code
    private var $_DbtrBIC;   

    //2.72 <Dbtr> [1..1], <Nm> [0..1]
    //Debitor Name 
    //Format: Text, Maxlenght: 70 
    private var $_DbtrNm ;  

    //2.73 <DbtrAcct> [1..1], <Id> [1..1], <IBAN> [1..1]
    //Debtor Account, Id, IBAN
    //Format: Debitor IBAN code 
    private var $_DbtrIBAN;

    //2.88 <RmtInf> [0..1], <Ustrd> [0..n]
    //RemittanceInformation, Unstructured
    //Format: Text, MaxLength: 140
    private var $_Description;

    // property <EndToEndId>
    public function setEndToEndId($Value) {
        $this->_EndToEndId = substr($Value,0,34);
    }

    // property <InstdAmt>
    public function setInstdAmt($Value) {
        $this->_InstdAmt = $Value;
    }

    // property <InstdAmt>
    public function getInstdAmt() {
        return $this->_InstdAmt;
    }

    // property <MndtId>
    public function setMndtId($Value) {
        $this->_MndtId = $Value;
    }

    // property <MndtId>
    public function setDtOfSgntr($Value) {
        $this->_DtOfSgntr = $Value;
    }

    // property <Dbtr BIC>
    public function setDbtrBIC($Value) {
        $this->_DbtrBIC = $Value;
    }

    // property <DbtrNm>
    public function setDbtrNm($Value) {
        $this->_DbtrNm = substr($Value,0,69);
    }

    // property <DbtrIBAN>
    public function setDbtrIBAN($Value) {
        $this->_DbtrIBAN = $Value;
    }

    // property <Description>
    public function setDescription($Value) {
        $this->_Description = substr($Value,0,139);
    }

    //creates and returns a DirectDebitTransactionInformation XML block
    public function getXMLDirectDebitTransactionInformation() {
        $XMLDirectDebitTransactionInformation = <<<XMLDDTI
                <DrctDbtTxInf>
                    <PmtId>
                        <EndToEndId>{$this->_EndToEndId}</EndToEndId>
                    </PmtId>
                    <InstdAmt Ccy="EUR">{$this->_InstdAmt}</InstdAmt>
                    <DrctDbtTx>
                        <MndtRltdInf>
                            <MndtId>{$this->_MndtId}</MndtId>
                            <DtOfSgntr>{$this->_DtOfSgntr}</DtOfSgntr>
                        </MndtRltdInf>
                    </DrctDbtTx>
                    <DbtrAgt>
                        <FinInstnId>
                            <BIC>{$this->_DbtrBIC}</BIC>
                        </FinInstnId>
                    </DbtrAgt>
                    <Dbtr>
                        <Nm>{$this->_DbtrNm}</Nm>
                    </Dbtr>
                    <DbtrAcct>
                        <Id>
                            <IBAN>{$this->_DbtrIBAN}</IBAN>
                        </Id>
                    </DbtrAcct>
                    <RmtInf>
                        <Ustrd>{$this->_Description}</Ustrd>
                    </RmtInf>
                </DrctDbtTxInf>
XMLDDTI;

        return $XMLDirectDebitTransactionInformation;
    }
}







?>
