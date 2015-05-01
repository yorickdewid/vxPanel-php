<?php

/**
 * This class holds the settings for the TransIP API.
 *
 * @package Transip
 * @class ApiSettings
 * @author TransIP (support@transip.nl)
 */
class Transip_ApiSettings
{
	/**
	 * The mode in which the API operates, can be either:
	 *		readonly
	 *		readwrite
	 *
	 * In readonly mode, no modifying functions can be called.
	 * To make persistent changes, readwrite mode should be enabled.
	 */
	//public static $mode = 'readwrite';
	public static $mode = 'readonly';

	 /**
	 * TransIP API endpoint to connect to.
	 *
	 * e.g.:
	 *
	 * 		'api.transip.nl'
	 * 		'api.transip.be'
	 * 		'api.transip.eu'
	 */
	public static $endpoint = 'api.transip.nl';

	/**
	 * Your login name on the TransIP website.
	 *
	 */
	public static $login = 'ariekaas';

	/**
	 * One of your private keys; these can be requested via your Controlpanel
	 */
	public static $privateKey = '-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDvFsZdE493+bzS
nHq+HANmZ+6jK8+CehvWuIjlSjxrWEUy+GMOjhiwkxFvdUytMiPoTZjOV3QV+0PQ
K0AD5ejQEF/pJ0FPnEtjx11nXeIY8R14gHpIQ7OXWMIw04q16yUDaqwEEKlym2ya
S2iAtGMv911scV4CDx4lcPM2Q4vvLICCs2nvM0pqvyVde760hE1cJN5aa59Oe2ia
ALmVa4d/+3RUtPhJe1yLimPRMBO86m4iGRRpdJDjtdjoahGaYoY97FlSQdj6hYKK
KgameU/x5B29LEvQDCUviUVxWyWDUlRWmiprTzAfSYtx9u37qd1AcDB+dgCCR2I6
1tasKtjFAgMBAAECggEARBiugtARatB8kVf0NbVw2nf7pgnSXo085JlGlFfigYhw
nSXVwM2EBra8noxAPIwtYfrH7O1HqQMZu262GuYeuzQOvS9rEKupZU/hMJGy1fvG
sw2UxoRR4GdtV87IkSvaLPDy8W11dakC86dKqm22WnEP8NURO3Dm2y8idnAiqQns
2FjNx1JoQDKJdwF0hiQXNU8ekqkWKOWeZPwiOGDDIFmvH2CwFH6px2go7hW2ixRR
eGqg81m+uK0tAruPqfQE1N9lWVxHFoeZgbigZOARItQlqkMUgcN2eQ5O7H8B/gb7
OKyTUqqLt66j+eFiIi9VslciHvF4ku3KsOZ9tR3y+QKBgQD6EMmgj77ieW6EaHPj
dhI0EN4S3RaMrdAHsFdX897HIK4fjbqELlZZOa4SCIsfIyqEspAm+6X7xB32wOFE
Vt/n7ieeaeyPgVEhUKAffaOsTevZHBNMmYxCS039wa9Yr8RiRB5PbuLcEBcTqDoG
MsH+uldS74gnqEJXomQAI3u/GwKBgQD0w00wiJ+5IDVsWoHlniqZUOUltVke/6Iz
CtEVNI8I74P6YeAn8gR6TuYpfIS+m719kpamojAX9OMLb+nChJra+TmhvokfabKO
IuFKvFEgfM9rMItCBQtjxp/WSAsOZXBcAoXI8qHyp2YYU3hw6TmcZUeubuyllJAk
UJ8u6tTlnwKBgQCb3HrPqMjBQA6yWKhizeTqrti7yTGU3YEcSb0nBMmGM8hTwnLO
kFMhqeHlO437CmAl9WYD5jW+hq5mbjH59mv4k2f+ROc9SszWhvpGpnitnKOz3tvo
kovphiZGU8KG9Ibi+LIrsGGV6DF5/xCrbFed/WcQOlwS27z+AilyLKukoQKBgQDe
SoFQEsT83MOoJIrUf1Ew7Mcv+AN4o7IrqSc6mwFMnDrAqyNpI+PtMLrX8r6cXdU6
tmcb7zC+kSmiuYETqFr7hF1TCDiAzv3bGNKDjYQgfTnjn7LZwrsVYs7HTaa87GOf
LKtUVQe/2Uhfz28THL21zEXNpfBGcSZ31MC9W4j/AQKBgQDM7BOZ+ET+Q039PyDL
LIX7pvu/JhFKNCe3c0kdd/+XCNMd4O1w1ePF4FwHIDpFbWLeOFs7f1XswmKI5bPJ
CX26z3l3uSxMx3MBgqBnkSNkj1k6xhX0gXL7iIB210K9JHBRGfHmNYv2PbX0PYNl
zxUGnJeuZ05nJKznTax+bC+BWQ==
-----END PRIVATE KEY-----';
}
