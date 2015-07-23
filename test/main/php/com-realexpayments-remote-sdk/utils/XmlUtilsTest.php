<?php


namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\Amount;
use com\realexpayments\remote\sdk\domain\Card;
use com\realexpayments\remote\sdk\domain\CardType;
use com\realexpayments\remote\sdk\domain\CVN;
use com\realexpayments\remote\sdk\domain\payment\Address;
use com\realexpayments\remote\sdk\domain\payment\AutoSettle;
use com\realexpayments\remote\sdk\domain\payment\CardIssuer;
use com\realexpayments\remote\sdk\domain\payment\Comment;
use com\realexpayments\remote\sdk\domain\payment\Mpi;
use com\realexpayments\remote\sdk\domain\payment\PaymentRequest;
use com\realexpayments\remote\sdk\domain\payment\PaymentResponse;
use com\realexpayments\remote\sdk\domain\payment\PaymentType;
use com\realexpayments\remote\sdk\domain\payment\Recurring;
use com\realexpayments\remote\sdk\domain\payment\TssInfo;
use com\realexpayments\remote\sdk\domain\payment\TssResult;
use com\realexpayments\remote\sdk\domain\payment\TssResultCheck;

/**
 * Unit test class for XmlUtils.
 *
 * @author vicpada
 */
class XmlUtilsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using the helper methods.
	 */
	public function testPaymentRequestXmlHelpers() {
		$cvn = ( new CVN() )
			->addNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER )
			->addPresenceIndicatorType( SampleXmlValidationUtils::$CARD_CVN_PRESENCE );

		$card = ( new Card() )
			->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
			->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
			->addType( new CardType( CardType::VISA ) )
			->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
			->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$card->setCvn( $cvn );

		$tssInfo = ( new TssInfo() )
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( ( new Address() )
				->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS ) )
			->addAddress( ( new Address() )
				->addAddressType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING ) );

		$request = ( new PaymentRequest() )
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addPaymentType( new PaymentType( PaymentType::AUTH ) )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( ( new AutoSettle() )->addAutoSettleFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG ) )
			->addTimestamp( SampleXmlValidationUtils::TIMESTAMP )
			->addChannel( SampleXmlValidationUtils::CHANNEL )
			->addOrderId( SampleXmlValidationUtils::ORDER_ID )
			->addHash( SampleXmlValidationUtils::REQUEST_HASH )
			->addComment( SampleXmlValidationUtils::COMMENT1 )
			->addComment( SampleXmlValidationUtils::COMMENT2 )
			->addPaymentsReference( SampleXmlValidationUtils::PASREF )
			->addAuthCode( SampleXmlValidationUtils::AUTH_CODE )
			->addRefundHash( SampleXmlValidationUtils::REFUND_HASH )
			->addFraudFilter( SampleXmlValidationUtils::FRAUD_FILTER )
			->addRecurring( new Recurring() )// TODO: Add recurring info
			->addTssInfo( $tssInfo )
			->addMpi( new Mpi() ); // TODO: Add 3DS info

		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = ( new PaymentRequest() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using the helper methods with no enums.
	 */
	public function  testPaymentRequestXmlHelpersNoEnums() {
		$card = ( new Card() )
			->addExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE )
			->addNumber( SampleXmlValidationUtils::CARD_NUMBER )
			->addType( new CardType( CardType::VISA ) )
			->addCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME )
			->addCvn( SampleXmlValidationUtils::CARD_CVN_NUMBER )
			->addCvnPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() )
			->addIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );


		$tssInfo = ( new TssInfo() )
			->addCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER )
			->addProductId( SampleXmlValidationUtils::PRODUCT_ID )
			->addVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE )
			->addCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP )
			->addAddress( ( new Address() )
				->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS->getAddressType() )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS ) )
			->addAddress( ( new Address() )
				->addType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING->getAddressType() )
				->addCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING )
				->addCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING ) );

		$request = ( new PaymentRequest() )
			->addAccount( SampleXmlValidationUtils::ACCOUNT )
			->addMerchantId( SampleXmlValidationUtils::MERCHANT_ID )
			->addType( ( new PaymentType( PaymentType::AUTH ) )->getType() )
			->addAmount( SampleXmlValidationUtils::AMOUNT )
			->addCurrency( SampleXmlValidationUtils::CURRENCY )
			->addCard( $card )
			->addAutoSettle( ( new AutoSettle() )->addFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() ) )
			->addTimestamp( SampleXmlValidationUtils::TIMESTAMP )
			->addChannel( SampleXmlValidationUtils::CHANNEL )
			->addOrderId( SampleXmlValidationUtils::ORDER_ID )
			->addHash( SampleXmlValidationUtils::REQUEST_HASH )
			->addComment( SampleXmlValidationUtils::COMMENT1 )
			->addComment( SampleXmlValidationUtils::COMMENT2 )
			->addPaymentsReference( SampleXmlValidationUtils::PASREF )
			->addAuthCode( SampleXmlValidationUtils::AUTH_CODE )
			->addRefundHash( SampleXmlValidationUtils::REFUND_HASH )
			->addFraudFilter( SampleXmlValidationUtils::FRAUD_FILTER )
			->addRecurring( new Recurring() )// TODO: Add recurring info
			->addTssInfo( $tssInfo )
			->addMpi( new Mpi() ); // TODO: Add 3DS info


		// convert to XML
		$xml = $request->toXml();

		// Convert from XML back to PaymentRequest

		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = ( new PaymentRequest() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentRequest} to and from XML using setters.
	 */
	public function testPaymentRequestXmlSetters() {
		$card = new Card();
		$card->setExpiryDate( SampleXmlValidationUtils::CARD_EXPIRY_DATE );
		$card->setNumber( SampleXmlValidationUtils::CARD_NUMBER );
		$card->setType( SampleXmlValidationUtils::$CARD_TYPE->getType() );
		$card->setCardHolderName( SampleXmlValidationUtils::CARD_HOLDER_NAME );
		$card->setIssueNumber( SampleXmlValidationUtils::CARD_ISSUE_NUMBER );

		$cvn = new Cvn();
		$cvn->setNumber( SampleXmlValidationUtils::CARD_CVN_NUMBER );
		$cvn->setPresenceIndicator( SampleXmlValidationUtils::$CARD_CVN_PRESENCE->getIndicator() );
		$card->setCvn( $cvn );

		$request = new PaymentRequest();
		$request->setAccount( SampleXmlValidationUtils::ACCOUNT );
		$request->setMerchantId( SampleXmlValidationUtils::MERCHANT_ID );
		$request->setType( ( new PaymentType( PaymentType::AUTH ) )->getType() );

		$amount = new Amount();
		$amount->setAmount( SampleXmlValidationUtils::AMOUNT );
		$amount->setCurrency( SampleXmlValidationUtils::CURRENCY );
		$request->setAmount( $amount );

		$autoSettle = new AutoSettle();
		$autoSettle->setFlag( SampleXmlValidationUtils::$AUTO_SETTLE_FLAG->getFlag() );

		$request->setAutoSettle( $autoSettle );
		$request->setCard( $card );
		$request->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP );
		$request->setChannel( SampleXmlValidationUtils::CHANNEL );
		$request->setOrderId( SampleXmlValidationUtils::ORDER_ID );
		$request->setHash( SampleXmlValidationUtils::REQUEST_HASH );

		$comments = array();
		$comment  = new Comment();
		$comment->setId( 1 );
		$comment->setComment( SampleXmlValidationUtils::COMMENT1 );
		$comments[] = $comment;
		$comment    = new Comment();
		$comment->setId( 2 );
		$comment->setComment( SampleXmlValidationUtils::COMMENT2 );
		$comments[] = $comment;
		$request->setComments( $comments );

		$request->setPaymentsReference( SampleXmlValidationUtils::PASREF );
		$request->setAuthCode( SampleXmlValidationUtils::AUTH_CODE );
		$request->setRefundHash( SampleXmlValidationUtils::REFUND_HASH );
		$request->setFraudFilter( SampleXmlValidationUtils::FRAUD_FILTER );

		$recurring = new Recurring();
		// TODO: Next iteration
		//$recurring->setFlag( SampleXmlValidationUtils::RECURRING_FLAG->getRecurringFlag());
		//$recurring->setSequence( SampleXmlValidationUtils::RECURRING_SEQUENCE->getSequence());
		//$recurring->setType( SampleXmlValidationUtils::RECURRING_TYPE->getType());
		$request->setRecurring( $recurring );

		$tssInfo = new TssInfo();
		$tssInfo->setCustomerNumber( SampleXmlValidationUtils::CUSTOMER_NUMBER );
		$tssInfo->setProductId( SampleXmlValidationUtils::PRODUCT_ID );
		$tssInfo->setVariableReference( SampleXmlValidationUtils::VARIABLE_REFERENCE );
		$tssInfo->setCustomerIpAddress( SampleXmlValidationUtils::CUSTOMER_IP );

		$addresses = array();
		$address   = new Address();
		$address->setType( SampleXmlValidationUtils::$ADDRESS_TYPE_BUSINESS->getAddressType() );
		$address->setCode( SampleXmlValidationUtils::ADDRESS_CODE_BUSINESS );
		$address->setCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_BUSINESS );
		$addresses[] = $address;

		$address = new Address();
		$address->setType( SampleXmlValidationUtils::$ADDRESS_TYPE_SHIPPING->getAddressType() );
		$address->setCode( SampleXmlValidationUtils::ADDRESS_CODE_SHIPPING );
		$address->setCountry( SampleXmlValidationUtils::ADDRESS_COUNTRY_SHIPPING );
		$addresses[] = $address;

		$tssInfo->setAddresses( $addresses );
		$request->setTssInfo( $tssInfo );

		$mpi = new Mpi();
		// TODO: Next iteration
		//$mpi->setCavv( SampleXmlValidationUtils::THREE_D_SECURE_CAVV );
		//$mpi->setXid( SampleXmlValidationUtils::THREE_D_SECURE_XID );
		//$mpi->setEci( SampleXmlValidationUtils::THREE_D_SECURE_ECI );
		$request->setMpi( $mpi );

		//convert to XML
		$xml = $request->toXml();

		//Convert from XML back to PaymentRequest
		/* @var PaymentRequest $fromXmlRequest */
		$fromXmlRequest = ( new PaymentRequest() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentRequest( $fromXmlRequest, $this );
	}

	/**
	 * Tests conversion of {@link PaymentResponse} to and from XML.
	 */
	public function testPaymentResponseXml() {

		$response = new PaymentResponse();

		$response->setAccount( SampleXmlValidationUtils::ACCOUNT );
		$response->setAcquirerResponse( SampleXmlValidationUtils::ACQUIRER_RESPONSE );
		$response->setAuthCode( SampleXmlValidationUtils::AUTH_CODE );
		$response->setAuthTimeTaken( SampleXmlValidationUtils::AUTH_TIME_TAKEN );
		$response->setBatchId( SampleXmlValidationUtils::BATCH_ID );

		$cardIssuer = new CardIssuer();
		$cardIssuer->setBank( SampleXmlValidationUtils::BANK );
		$cardIssuer->setCountry( SampleXmlValidationUtils::COUNTRY );
		$cardIssuer->setCountryCode( SampleXmlValidationUtils::COUNTRY_CODE );
		$cardIssuer->setRegion( SampleXmlValidationUtils::REGION );
		$response->setCardIssuer( $cardIssuer );

		$response->setCvnResult( SampleXmlValidationUtils::CVN_RESULT );
		$response->setMerchantId( SampleXmlValidationUtils::MERCHANT_ID );
		$response->setMessage( SampleXmlValidationUtils::MESSAGE );
		$response->setOrderId( SampleXmlValidationUtils::ORDER_ID );
		$response->setPaymentsReference( SampleXmlValidationUtils::PASREF );
		$response->setResult( SampleXmlValidationUtils::RESULT_SUCCESS );
		$response->setHash( SampleXmlValidationUtils::RESPONSE_HASH );
		$response->setTimeStamp( SampleXmlValidationUtils::TIMESTAMP );
		$response->setTimeTaken( SampleXmlValidationUtils::TIME_TAKEN );

		$tssResult = new TssResult();
		$tssResult->setResult( SampleXmlValidationUtils::TSS_RESULT );

		$checks = array();
		$check  = new TssResultCheck();
		$check->setId( SampleXmlValidationUtils::TSS_RESULT_CHECK1_ID );
		$check->setValue( SampleXmlValidationUtils::TSS_RESULT_CHECK1_VALUE );
		$checks[] = $check;
		$check    = new TssResultCheck();
		$check->setId( SampleXmlValidationUtils::TSS_RESULT_CHECK2_ID );
		$check->setValue( SampleXmlValidationUtils::TSS_RESULT_CHECK2_VALUE );
		$checks[] = $check;

		$tssResult->setChecks( $checks );
		$response->setTssResult( $tssResult );

		$response->setAvsAddressResponse( SampleXmlValidationUtils::AVS_ADDRESS );
		$response->setAvsPostcodeResponse( SampleXmlValidationUtils::AVS_POSTCODE );

		//marshal to XML
		$xml = $response->toXml();

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = ( new PaymentResponse() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse, $this );
	}

	/**
	 * Tests conversion of {@link PaymentResponse} from XML file
	 */
	public function testPaymentResponseXmlFromFile() {
		$path = SampleXmlValidationUtils::PAYMENT_RESPONSE_XML_PATH;
		$prefix = __DIR__ . '/../../../resources';
		$xml  = file_get_contents( $prefix . $path );

		//unmarshal back to response
		/* @var PaymentResponse $fromXmlResponse */
		$fromXmlResponse = ( new PaymentResponse() )->fromXml( $xml );
		SampleXmlValidationUtils::checkUnmarshalledPaymentResponse( $fromXmlResponse, $this );
	}


}
