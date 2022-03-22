<?php

namespace Facebook\BusinessExtension\Helper;

use Exception;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Shipment\Track;
use Psr\Log\LoggerInterface;

class ShippingHelper extends AbstractHelper
{
    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param RegionFactory $regionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        RegionFactory $regionFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->regionFactory = $regionFactory;
        $this->logger = $logger;
    }

    /**
     * Array of FB supported shipping carriers
     *
     * Format: CARRIER_CODE => Carrier Title
     * Source: https://developers.facebook.com/docs/commerce-platform/order-management/carrier-codes
     *
     * @return array
     */
    public function getFbSupportedShippingCarriers()
    {
        return [
            "AUSTRALIA_POST" => "Australia Post",
            "CANADA_POST" => "Canada Post",
            "DHL" => "DHL",
            "DHL_ECOMMERCE_US" => "DHL eCommerce US",
            "EAGLE" => "Eagle",
            "FEDEX" => "FedEx",
            "FEDEX_UK" => "FedEx UK",
            "NEW_ZEALAND_POST" => "New Zealand Post",
            "ONTRAC" => "OnTrac",
            "POST_DANMARK" => "Post Danmark",
            "PUROLATOR" => "Purolator",
            "ROYAL_MAIL" => "Royal Mail",
            "SPEE_DEE" => "Spee-Dee",
            "TNT" => "TNT",
            "TNT_POST" => "TNT Post",
            "UPS" => "UPS",
            "USPS" => "USPS",
            "OTHER" => "Other tracking number",
            "ABF_FREIGHT" => "ABF Freight",
            "ABX_EXPRESS" => "ABX Express",
            "AB_CUSTOM_GROUP" => "AB Custom Group",
            "ACOMMERCE" => "aCommerce",
            "ACS_COURIER" => "ACS Courier",
            "ACS_WORLDWIDE_EXPRESS" => "ACS Worldwide Express",
            "ADICIONAL_LOGISTICS" => "Adicional Logistics",
            "ADSONE" => "ADSOne",
            "AIR21" => "AIR21",
            "AIRPAK_EXPRESS" => "Airpak Express",
            "AIRSPEED_INTERNATIONAL_CORPORATION" => "Airspeed International Corporation",
            "ALFATREX" => "AlfaTrex",
            "ALLIED_EXPRESS" => "Allied Express",
            "ALLJOY_SUPPLY_CHAIN_CO_LTD" => "ALLJOY SUPPLY CHAIN CO., LTD",
            "ALPHAFAST" => "alphaFAST",
            "AMAZON_FBA_USA" => "Amazon FBA USA",
            "AMAZON_LOGISTICS" => "Amazon Logistics",
            "AN_POST" => "An Post",
            "APC_OVERNIGHT" => "APC Overnight",
            "APC_OVERNIGHT_REFERENCE" => "APC Overnight Reference",
            "APC_POSTAL_LOGISTICS" => "APC Postal Logistics",
            "APRISA_EXPRESS" => "Aprisa Express",
            "ARAMEX" => "Aramex",
            "ARROW_XL" => "Arrow XL",
            "ASENDIA_GERMANY" => "Asendia Germany",
            "ASENDIA_HK" => "Asendia HK",
            "ASENDIA_HK_PREMIUM_SERVICE_LATAM" => "Asendia HK - Premium Service (LATAM)",
            "ASENDIA_UK" => "Asendia UK",
            "ASENDIA_USA" => "Asendia USA",
            "ASM" => "ASM",
            "AUPOST_CHINA" => "AuPost China",
            "AUSTRALIA_POST_SFTP" => "Australia Post Sftp",
            "AUSTRIAN_POST_EXPRESS" => "Austrian Post (Express)",
            "AUSTRIAN_POST_REGISTERED" => "Austrian Post (Registered)",
            "AXL_EXPRESS_LOGISTICS" => "AXL Express & Logistics",
            "A_DUIE_PYLE" => "A Duie Pyle",
            "A_J_EXPRESS" => "a j express",
            "B2C_EUROPE" => "B2C Europe",
            "BELPOST" => "Belpost",
            "BERT_TRANSPORT" => "Bert Transport",
            "BEST_EXPRESS" => "Best Express",
            "BEST_WAY_PARCEL" => "Best Way Parcel",
            "BIRDSYSTEM" => "BirdSystem",
            "BJS_DISTRIBUTION_STORAGE_COURIERS" => "BJS Distribution, Storage & Couriers",
            "BJS_DISTRIBUTION_STORAGE_COURIERS_FTP" => "BJS Distribution, Storage & Couriers - FTP",
            "BLUECARE_EXPRESS_LTD" => "Bluecare Express Ltd",
            "BLUEDART" => "Bluedart",
            "BLUE_STAR" => "Blue Star",
            "BNEED" => "Bneed",
            "BONDS_COURIERS" => "Bonds Couriers",
            "BOXC" => "BoxC",
            "BPOST" => "Bpost",
            "BPOST_INTERNATIONAL" => "Bpost international",
            "BRAZIL_CORREIOS" => "Brazil Correios",
            "BRT_BARTOLINI" => "BRT Bartolini",
            "BRT_BARTOLINI_PARCEL_ID" => "BRT Bartolini(Parcel ID)",
            "BULGARIAN_POSTS" => "Bulgarian Posts",
            "BUYLOGIC" => "Buylogic",
            "CAMBODIA_POST" => "Cambodia Post",
            "CANPAR_COURIER" => "Canpar Courier",
            "CAPITAL_TRANSPORT" => "Capital Transport",
            "CARRIER_007EX" => "007EX",
            "CARRIER_17_POST_SERVICE" => "17 Post Service",
            "CARRIER_2GO" => "2GO",
            "CARRIER_360_LION_EXPRESS" => "360 Lion Express",
            "CARRIER_4PX" => "4PX",
            "CARRIER_4_72_ENTREGANDO" => "4-72 Entregando",
            "CARRIER_ECHO" => "Echo",
            "CBL_LOGISTICS" => "CBL Logistics",
            "CELERITAS_TRANSPORTE_SL" => "Celeritas Transporte, S.L",
            "CESKA_POSTA" => "Česká Poš",
            "CHINA_EMS_EPACKET" => "China EMS (ePacket)",
            "CHINA_POST" => "China Post",
            "CHIT_CHATS" => "Chit Chats",
            "CHRONOPOST_FRANCE" => "Chronopost France",
            "CHRONOPOST_PORTUGAL" => "Chronopost Portugal",
            "CH_ROBINSON_WORLDWIDE_INC" => "C.H. Robinson Worldwide, Inc.",
            "CITY_LINK_EXPRESS" => "City-Link Express",
            "CJ_CENTURY" => "CJ Century",
            "CJ_CENTURY_INTERNATIONAL" => "CJ Century (International)",
            "CJ_GLS" => "CJ GLS",
            "CJ_KOREA_EXPRESS" => "CJ Korea Express",
            "CJ_LOGISTICS_INTERNATIONAL" => "CJ Logistics International",
            "CJ_TRANSNATIONAL_PHILIPPINES" => "CJ Transnational Philippines",
            "CLEVY_LINKS" => "Clevy Links",
            "CLOUDWISH_ASIA" => "Cloudwish Asia",
            "CNE_EXPRESS" => "CNE Express",
            "COLISSIMO" => "Colissimo",
            "COLIS_PRIVE" => "Colis Privé",
            "COLLECTCO" => "CollectCo",
            "COLLECT_PLUS" => "Collect+",
            "CON_WAY_FREIGHT" => "Con-way Freight",
            "COPA_AIRLINES_COURIER" => "Copa Airlines Courier",
            "CORREOS_CHILE" => "Correos Chile",
            "CORREOS_DE_COSTA_RICA" => "Correos de Costa Rica",
            "CORREOS_DE_ESPANA" => "Correos de España",
            "CORREOS_DE_MEXICO" => "Correos de Mexico",
            "CORREOS_EXPRESS" => "Correos Express",
            "CORREO_ARGENTINO" => "Correo Argentino",
            "COSMETICS_NOW" => "Cosmetics Now",
            "COUREX" => "Courex",
            "COURIERPOST" => "CourierPost",
            "COURIERS_PLEASE" => "Couriers Please",
            "COURIER_IT" => "Courier IT",
            "COURIER_PLUS" => "Courier Plus",
            "CPACKET" => "cPacket",
            "CUCKOO_EXPRESS" => "Cuckoo Express",
            "CYPRUS_POST" => "Cyprus Post",
            "DACHSER" => "DACHSER",
            "DAWN_WING" => "Dawn Wing",
            "DAYLIGHT_TRANSPORT_LLC" => "Daylight Transport, LLC",
            "DB_SCHENKER" => "DB Schenker",
            "DB_SCHENKER_SWEDEN" => "DB Schenker Sweden",
            "DD_EXPRESS_COURIER" => "DD Express Courier",
            "DELCART" => "Delcart",
            "DELHIVERY" => "Delhivery",
            "DELIVERYONTIME_LOGISTICS_PVT_LTD" => "DELIVERYONTIME LOGISTICS PVT LTD",
            "DELTEC_COURIER" => "Deltec Courier",
            "DEMANDSHIP" => "DemandShip",
            "DETRACK" => "Detrack",
            "DEUTSCHE_POST_DHL" => "Deutsche Post DHL",
            "DEUTSCHE_POST_MAIL" => "Deutsche Post Mail",
            "DEX_I" => "DEX-I",
            "DHL_2_MANN_HANDLING" => "DHL 2-Mann-Handling",
            "DHL_ACTIVE_TRACING" => "DHL Active Tracing",
            "DHL_BENELUX" => "DHL Benelux",
            "DHL_ECOMMERCE_ASIA" => "DHL eCommerce Asia",
            "DHL_EXPRESS_PIECE_ID" => "DHL Express (Piece ID)",
            "DHL_GLOBAL_FORWARDING" => "DHL Global Forwarding",
            "DHL_HONG_KONG" => "DHL Hong Kong",
            "DHL_NETHERLANDS" => "DHL Netherlands",
            "DHL_PARCEL_NL" => "DHL Parcel NL",
            "DHL_PARCEL_SPAIN" => "DHL Parcel Spain",
            "DHL_POLAND_DOMESTIC" => "DHL Poland Domestic",
            "DHL_SPAIN_DOMESTIC" => "DHL Spain Domestic",
            "DIMERCO_EXPRESS_GROUP" => "Dimerco Express Group",
            "DIRECTLOG" => "Directlog",
            "DIRECT_FREIGHT_EXPRESS" => "Direct Freight Express",
            "DIRECT_LINK" => "Direct Link",
            "DMM_NETWORK" => "DMM Network",
            "DOORA_LOGISTICS" => "Doora Logistics",
            "DOTZOT" => "Dotzot",
            "DPD" => "DPD",
            "DPD_FRANCE" => "DPD France",
            "DPD_GERMANY" => "DPD Germany",
            "DPD_HK" => "DPD HK",
            "DPD_IRELAND" => "DPD Ireland",
            "DPD_LOCAL" => "DPD Local",
            "DPD_LOCAL_REFERENCE" => "DPD Local reference",
            "DPD_POLAND" => "DPD Poland",
            "DPD_ROMANIA" => "DPD Romania",
            "DPD_RUSSIA" => "DPD Russia",
            "DPD_UK" => "DPD UK",
            "DPEX" => "DPEX",
            "DPEX_CHINA" => "DPEX China",
            "DPE_EXPRESS" => "DPE Express",
            "DPE_SOUTH_AFRICA" => "DPE South Africa",
            "DSV" => "DSV",
            "DTDC_AUSTRALIA" => "DTDC Australia",
            "DTDC_EXPRESS_GLOBAL_PTE_LTD" => "DTDC Express Global PTE LTD",
            "DTDC_INDIA" => "DTDC India",
            "DX" => "DX",
            "DX_FREIGHT" => "DX Freight",
            "DYNALOGIC_BENELUX_BV" => "Dynalogic Benelux BV",
            "DYNAMIC_LOGISTICS" => "Dynamic Logistics",
            "EASY_MAIL" => "Easy Mail",
            "ECARGO" => "Ecargo",
            "ECMS_INTERNATIONAL_LOGISTICS_CO_LTD" => "ECMS International Logistics Co., Ltd.",
            "ECOM_EXPRESS" => "Ecom Express",
            "EC_FIRSTCLASS" => "EC-Firstclass",
            "EFS_E_COMMERCE_FULFILLMENT_SERVICE" => "EFS (E-commerce Fulfillment Service)",
            "EKART" => "Ekart",
            "ELTA_HELLENIC_POST" => "ELTA Hellenic Post",
            "EMIRATES_POST" => "Emirates Post",
            "EMPS_EXPRESS" => "EMPS Express",
            "ENSENDA" => "Ensenda",
            "ENVIALIA" => "Envialia",
            "EPARCEL_KOREA" => "eParcel Korea",
            "EP_BOX" => "EP-Box",
            "EQUICK_CHINA" => "Equick China",
            "ESTAFETA" => "Estafeta",
            "ESTES" => "Estes",
            "ETOTAL_SOLUTION_LIMITED" => "eTotal Solution Limited",
            "EURODIS" => "Eurodis",
            "EXPEDITORS" => "Expeditors",
            "EZSHIP" => "EZship",
            "FASTRAK_SERVICES" => "Fastrak Services",
            "FASTWAY_AUSTRALIA" => "Fastway Australia",
            "FASTWAY_IRELAND" => "Fastway Ireland",
            "FASTWAY_NEW_ZEALAND" => "Fastway New Zealand",
            "FASTWAY_SOUTH_AFRICA" => "Fastway South Africa",
            "FEDEX_CROSS_BORDER" => "Fedex Cross Border",
            "FEDEX_FREIGHT" => "FedEx Freight",
            "FEDEX_POLAND_DOMESTIC" => "FedEx Poland Domestic",
            "FERCAM_LOGISTICS_TRANSPORT" => "FERCAM Logistics & Transport",
            "FIRST_FLIGHT_COURIERS" => "First Flight Couriers",
            "FIRST_LOGISTICS" => "First Logistics",
            "FLYT_EXPRESS" => "Flyt Express",
            "GATI_KWE" => "Gati-KWE",
            "GDEX" => "GDEX",
            "GENIKI_TAXYDROMIKI" => "Geniki Taxydromiki",
            "GEODIS_E_SPACE" => "Geodis E-space",
            "GEODIS_DISTRIBUTION_EXPRESS" => "GEODIS - Distribution & Express",
            "GIAO_HANG_NHANH" => "Giao hàng nhanh",
            "GLOBEGISTICS_INC" => "Globegistics Inc.",
            "GLS" => "GLS",
            "GLS_CZECH_REPUBLIC" => "GLS Czech Republic",
            "GLS_ITALY" => "GLS Italy",
            "GLS_NETHERLANDS" => "GLS Netherlands",
            "GOFLY" => "GoFly",
            "GOJAVAS" => "GoJavas",
            "GREYHOUND" => "Greyhound",
            "GSI_EXPRESS" => "GSI EXPRESS",
            "HERMESWORLD" => "Hermesworld",
            "HERMES_GERMANY" => "Hermes Germany",
            "HERMES_ITALY" => "Hermes Italy",
            "HOLISOL" => "Holisol",
            "HOMEDIRECT_LOGISTICS" => "Homedirect Logistics",
            "HONG_KONG_POST" => "Hong Kong Post",
            "HRVATSKA_POSTA" => "Hrvatska Pošta",
            "HUA_HAN_LOGISTICS" => "Hua Han Logistics",
            "HUNTER_EXPRESS" => "Hunter Express",
            "ICELAND_POST" => "Iceland Post",
            "IDEX" => "IDEX",
            "IMEX_GLOBAL_SOLUTIONS" => "IMEX Global Solutions",
            "IMX_MAIL" => "IMX Mail",
            "INDIA_POST_DOMESTIC" => "India Post Domestic",
            "INDIA_POST_INTERNATIONAL" => "India Post International",
            "INPOST_PACZKOMATY" => "InPost Paczkomaty",
            "INSTANT_TIONG_NAM_EBIZ_EXPRESS_SDN_BHD" => "INSTANT (Tiong Nam Ebiz Express Sdn Bhd)",
            "INTERNATIONAL_SEUR" => "International Seur",
            "INTERNET_EXPRESS" => "Internet Express",
            "ISRAEL_POST" => "Israel Post",
            "ISRAEL_POST_DOMESTIC" => "Israel Post Domestic",
            "ITALY_SDA" => "Italy SDA",
            "I_PARCEL" => "i-parcel",
            "J_T_EXPRESS" => "J&T EXPRESS",
            "JAM_EXPRESS" => "Jam Express",
            "JANCO_ECOMMERCE" => "Janco Ecommerce",
            "JAPAN_POST" => "Japan Post",
            "JAYON_EXPRESS_JEX" => "Jayon Express (JEX)",
            "JCEX" => "JCEX",
            "JERSEY_POST" => "Jersey Post",
            "JET_SHIP_WORLDWIDE" => "Jet-Ship Worldwide",
            "JINSUNG_TRADING" => "JINSUNG TRADING",
            "JNE" => "JNE",
            "JOCOM" => "Jocom",
            "JP_BH_POSTA" => "JP BH Pošta",
            "JX" => "JX",
            "J_NET" => "J-Net",
            "K1_EXPRESS" => "K1 Express",
            "KANGAROO_WORLDWIDE_EXPRESS" => "Kangaroo Worldwide Express",
            "KERRY_EXPRESS_HONG_KONG" => "Kerry Express Hong Kong",
            "KERRY_EXPRESS_THAILAND" => "Kerry Express Thailand",
            "KERRY_EXPRESS_VIETNAM_CO_LTD" => "Kerry Express (Vietnam) Co Ltd",
            "KGM_HUB" => "KGM Hub",
            "KIALA" => "Kiala",
            "KOREA_POST" => "Korea Post",
            "KOREA_POST_EMS" => "Korea Post EMS",
            "KRONOS_EXPRESS" => "Kronos Express",
            "KUEHNE_NAGEL" => "Kuehne + Nagel",
            "LANDMARK_GLOBAL" => "Landmark Global",
            "LAO_POST" => "Lao Post",
            "LASERSHIP" => "LaserShip",
            "LA_POSTE" => "La Poste",
            "LBC_EXPRESS" => "LBC Express",
            "LHT_EXPRESS" => "LHT Express",
            "LIETUVOS_PASTAS" => "Lietuvos Paštas",
            "LINE_CLEAR_EXPRESS_LOGISTICS_SDN_BHD" => "Line Clear Express & Logistics Sdn Bhd",
            "LINK_BRIDGE_BEIJING_INTERNATIONAL_LOGISTICS_COLTD" => "Link Bridge(BeiJing)international logistics co.,ltd",
            "LION_PARCEL" => "Lion Parcel",
            "LOGISTIC_WORLDWIDE_EXPRESS" => "Logistic Worldwide Express",
            "LOGWIN_LOGISTICS" => "Logwin Logistics",
            "LONE_STAR_OVERNIGHT" => "Lone Star Overnight",
            "MAGYAR_POSTA" => "Magyar Posta",
            "MAILAMERICAS" => "MailAmericas",
            "MAILPLUS" => "MailPlus",
            "MAINFREIGHT" => "Mainfreight",
            "MALAYSIA_POST_EMS_POS_LAJU" => "Malaysia Post EMS / Pos Laju",
            "MALAYSIA_POST_REGISTERED" => "Malaysia Post - Registered",
            "MARA_XPRESS" => "Mara Xpress",
            "MATDESPATCH" => "Matdespatch",
            "MATKAHUOLTO" => "Matkahuolto",
            "MDS_COLLIVERY_PTY_LTD" => "MDS Collivery Pty (Ltd)",
            "MEGASAVE" => "Megasave",
            "MEXICO_AEROFLASH" => "Mexico AeroFlash",
            "MEXICO_REDPACK" => "Mexico Redpack",
            "MEXICO_SENDA_EXPRESS" => "Mexico Senda Express",
            "MIKROPAKKET" => "Mikropakket",
            "MONDIAL_RELAY" => "Mondial Relay",
            "MRW" => "MRW",
            "MUDITA" => "MUDITA",
            "MXE_EXPRESS" => "MXE Express",
            "MYHERMES_UK" => "myHermes UK",
            "MYPOSTONLINE" => "Mypostonline",
            "M_XPRESS_SDN_BHD" => "M Xpress Sdn Bhd",
            "NACEX_SPAIN" => "NACEX Spain",
            "NANJING_WOYUAN" => "Nanjing Woyuan",
            "NATIONAL_SAMEDAY" => "National Sameday",
            "NATIONWIDE_EXPRESS" => "Nationwide Express",
            "NEWGISTICS" => "Newgistics",
            "NEWGISTICS_API" => "Newgistics API",
            "NEXIVE_TNT_POST_ITALY" => "Nexive (TNT Post Italy)",
            "NHANS_SOLUTIONS" => "Nhans Solutions",
            "NIGHTLINE" => "Nightline",
            "NIM_EXPRESS" => "Nim Express",
            "NINJA_VAN" => "Ninja Van",
            "NINJA_VAN_INDONESIA" => "Ninja Van Indonesia",
            "NINJA_VAN_MALAYSIA" => "Ninja Van Malaysia",
            "NINJA_VAN_PHILIPPINES" => "Ninja Van Philippines",
            "NINJA_VAN_THAILAND" => "Ninja Van Thailand",
            "NIPOST" => "NiPost",
            "NORSK_GLOBAL" => "Norsk Global",
            "NOVA_POSHTA" => "Nova Poshta",
            "NOVA_POSHTA_INTERNATIONAL" => "Nova Poshta (International)",
            "OCA_ARGENTINA" => "OCA Argentina",
            "OLD_DOMINION_FREIGHT_LINE" => "Old Dominion Freight Line",
            "OMNIVA" => "Omniva",
            "OMNI_PARCEL" => "Omni Parcel",
            "ONE_WORLD_EXPRESS" => "One World Express",
            "PACKLINK" => "Packlink",
            "PAL_EXPRESS_LIMITED" => "PAL Express Limited",
            "PANDU_LOGISTICS" => "Pandu Logistics",
            "PANTHER" => "Panther",
            "PANTHER_ORDER_NUMBER" => "Panther Order Number",
            "PANTHER_REFERENCE" => "Panther Reference",
            "PAQUETEXPRESS" => "Paquetexpress",
            "PARCELLEDIN" => "Parcelled.in",
            "PARCELPOINT_PTY_LTD" => "ParcelPoint Pty Ltd",
            "PARCEL_FORCE" => "Parcel Force",
            "PARCEL_POST_SINGAPORE" => "Parcel Post Singapore",
            "PAYPAL_PACKAGE" => "PayPal Package",
            "PFC_EXPRESS" => "PFC Express",
            "PICKUPP" => "Pickupp",
            "PICK_UPP_MYS_SGP" => "PICK UPP",
            "PILOT_FREIGHT_SERVICES" => "Pilot Freight Services",
            "PITNEY_BOWES" => "Pitney Bowes",
            "PIXSELL_LOGISTICS" => "PIXSELL LOGISTICS",
            "POCZTA_POLSKA" => "Poczta Polska",
            "PORTUGAL_CTT" => "Portugal CTT",
            "PORTUGAL_SEUR" => "Portugal Seur",
            "POST56" => "Post56",
            "POSTEN_NORGE_BRING" => "Posten Norge / Bring",
            "POSTE_ITALIANE" => "Poste Italiane",
            "POSTE_ITALIANE_PACCOCELERE" => "Poste Italiane Paccocelere",
            "POSTI" => "Posti",
            "POSTNL_DOMESTIC" => "PostNL Domestic",
            "POSTNL_INTERNATIONAL" => "PostNL International",
            "POSTNL_INTERNATIONAL_3S" => "PostNL International 3S",
            "POSTNORD_DENMARK" => "PostNord Denmark",
            "POSTNORD_LOGISTICS" => "PostNord Logistics",
            "POSTNORD_SWEDEN" => "PostNord Sweden",
            "POST_OF_SLOVENIA" => "Post of Slovenia",
            "POST_SERBIA" => "Post Serbia",
            "POS_INDONESIA_DOMESTIC" => "Pos Indonesia Domestic",
            "POS_INDONESIA_INTL" => "Pos Indonesia Int'l",
            "POSTA_ROMANA" => "Poșta Română",
            "PROFESSIONAL_COURIERS" => "Professional Couriers",
            "PTT_POSTA" => "PTT Posta",
            "QUALITYPOST" => "QualityPost",
            "QUANTIUM" => "Quantium",
            "QXPRESS" => "Qxpress",
            "RABEN_GROUP" => "Raben Group",
            "RAF_PHILIPPINES" => "RAF Philippines",
            "RAIDEREX" => "RaidereX",
            "RAM" => "RAM",
            "REDUR_SPAIN" => "Redur Spain",
            "RED_CARPET_LOGISTICS" => "Red Carpet Logistics",
            "RINCOS" => "Rincos",
            "RL_CARRIERS" => "RL Carriers",
            "ROADBULL_LOGISTICS" => "Roadbull Logistics",
            "ROCKET_PARCEL_INTERNATIONAL" => "Rocket Parcel International",
            "RPD2MAN_DELIVERIES" => "RPD2man Deliveries",
            "RPX_INDONESIA" => "RPX Indonesia",
            "RPX_ONLINE" => "RPX Online",
            "RRD_INTERNATIONAL_LOGISTICS_USA" => "RRD International Logistics U.S.A",
            "RUSSIAN_POST" => "Russian Post",
            "RUSTON" => "Ruston",
            "RZY_EXPRESS" => "RZY Express",
            "SAFEXPRESS" => "Safexpress",
            "SAGAWA" => "Sagawa",
            "SAIA_LTL_FREIGHT" => "Saia LTL Freight",
            "SAILPOST" => "SAILPOST",
            "SAP_EXPRESS" => "SAP EXPRESS",
            "SAUDI_POST" => "Saudi Post",
            "SCUDEX_EXPRESS" => "Scudex Express",
            "SEINO" => "Seino",
            "SEKO_LOGISTICS" => "SEKO Logistics",
            "SENDING_TRANSPORTE_URGENTE_Y_COMUNICACION_SAU" => "Sending Transporte Urgente y Comunicacion, S.A.U",
            "SENDIT" => "Sendit",
            "SENDLE" => "Sendle",
            "SFC_SERVICE" => "SFC Service",
            "SF_EXPRESS" => "S.F. Express",
            "SF_INTERNATIONAL" => "S.F International",
            "SGT_CORRIERE_ESPRESSO" => "SGT Corriere Espresso",
            "SHANGHAI_WISE_SUPPLY_CHAIN_MANAGEMENT_CO_LTD" => "上海万色供应链管理有限公司（原：上海万色速递有限公司） Shanghai Wise Supply Chain Management Co., Ltd",
            "SHENZHEN_JINGHUADA_LOGISTICS_CO_LTD" => "Shenzhen Jinghuada Logistics Co., Ltd",
            "SHIPPIT" => "Shippit",
            "SHIPTOR" => "Shiptor",
            "SHOPFANSRU_LLC" => "ShopfansRU LLC",
            "SHREE_MARUTI_COURIER_SERVICES_PVT_LTD" => "Shree Maruti Courier Services Pvt Ltd",
            "SHREE_TIRUPATI_COURIER_SERVICES_PVT_LTD" => "SHREE TIRUPATI COURIER SERVICES PVT. LTD.",
            "SHUNYOU_POST" => "Shunyou Post",
            "SIMPLYPOST" => "SimplyPost",
            "SINGAPORE_POST" => "Singapore Post",
            "SINGAPORE_SPEEDPOST" => "Singapore Speedpost",
            "SIODEMKA" => "Siodemka",
            "SKYBOX" => "SKYBOX",
            "SKYNET_MALAYSIA" => "SkyNet Malaysia",
            "SKYNET_WORLDWIDE_EXPRESS" => "SkyNet Worldwide Express",
            "SKYNET_WORLDWIDE_EXPRESS_UAE" => "SkyNet Worldwide Express UAE",
            "SKYNET_WORLDWIDE_EXPRESS_UK" => "Skynet Worldwide Express UK",
            "SKYNET_WORLD_WIDE_EXPRESS_SOUTH_AFRICA" => "Skynet World Wide Express South Africa",
            "SKYPOSTAL" => "SkyPostal",
            "SMOOTH_COURIERS" => "Smooth Couriers",
            "SMSA_EXPRESS" => "SMSA Express",
            "SOUTH_AFRICAN_POST_OFFICE" => "South African Post Office",
            "SPANISH_SEUR" => "Spanish Seur",
            "SPECIALISED_FREIGHT" => "Specialised Freight",
            "SPEEDEX_COURIER" => "Speedex Courier",
            "SPEED_COURIERS" => "Speed Couriers",
            "SPOTON_LOGISTICS_PVT_LTD" => "SPOTON Logistics Pvt Ltd",
            "SRE_KOREA" => "SRE Korea",
            "STARTRACK" => "StarTrack",
            "STAR_TRACK_COURIER" => "Star Track Courier",
            "STAR_TRACK_EXPRESS" => "Star Track Express",
            "STO_EXPRESS" => "STO Express",
            "SWISS_POST" => "Swiss Post",
            "TAIWAN_POST" => "Taiwan Post",
            "TAQBIN_HONG_KONG" => "TAQBIN Hong Kong",
            "TAQBIN_MALAYSIA" => "TAQBIN Malaysia",
            "TAQBIN_SINGAPORE" => "TAQBIN Singapore",
            "TCS" => "TCS",
            "TELIWAY_SIC_EXPRESS" => "Teliway SIC Express",
            "THAILAND_THAI_POST" => "Thailand Thai Post",
            "THE_COURIER_GUY" => "The Courier Guy",
            "TIKI" => "Tiki",
            "TIPSA" => "TIPSA",
            "TNT_AUSTRALIA" => "TNT Australia",
            "TNT_CLICK_ITALY" => "TNT-Click Italy",
            "TNT_FRANCE" => "TNT France",
            "TNT_ITALY" => "TNT Italy",
            "TNT_REFERENCE" => "TNT Reference",
            "TNT_UK_REFERENCE" => "TNT UK Reference",
            "TOLL_IPEC" => "Toll IPEC",
            "TOLL_PRIORITY" => "Toll Priority",
            "TOLOS" => "Tolos",
            "TRAKPAK" => "TrakPak",
            "TRANSMISSION" => "TransMission",
            "TRANS_KARGO_INTERNASIONAL" => "Trans Kargo Internasional",
            "TUFFNELLS_PARCELS_EXPRESS" => "Tuffnells Parcels Express",
            "UBI_SMART_PARCEL" => "UBI Smart Parcel",
            "UKRPOSHTA" => "UkrPoshta",
            "UK_MAIL" => "UK Mail",
            "UNITED_DELIVERY_SERVICE_LTD" => "United Delivery Service, Ltd",
            "UPS_FREIGHT" => "UPS Freight",
            "UPS_MAIL_INNOVATIONS" => "UPS Mail Innovations",
            "VIETNAM_POST" => "Vietnam Post",
            "VIETNAM_POST_EMS" => "Vietnam Post EMS",
            "VIETTELPOST" => "ViettelPost",
            "WAHANA" => "Wahana",
            "WANBEXPRESS" => "WanbExpress",
            "WEDO_LOGISTICS" => "WeDo Logistics",
            "WEPOST_LOGISTICS" => "WePost Logistics",
            "WHISTL" => "Whistl",
            "WISELOADS" => "Wiseloads",
            "WISE_EXPRESS" => "Wise Express",
            "WISHPOST" => "WishPost",
            "WNDIRECT" => "wnDirect",
            "XDP_EXPRESS" => "XDP Express",
            "XDP_EXPRESS_REFERENCE" => "XDP Express Reference",
            "XEND_EXPRESS" => "Xend Express",
            "XL_EXPRESS" => "XL Express",
            "XPOSTPH" => "Xpost.ph",
            "XPRESSBEES" => "XpressBees",
            "XQ_EXPRESS" => "XQ Express",
            "YAKIT" => "Yakit",
            "YAMATO_JAPAN" => "Yamato Japan",
            "YANWEN" => "Yanwen",
            "YODEL_DOMESTIC" => "Yodel Domestic",
            "YODEL_INTERNATIONAL" => "Yodel International",
            "YRC" => "YRC",
            "YTO_EXPRESS" => "YTO Express",
            "YUNDA_EXPRESS" => "Yunda Express",
            "YUN_EXPRESS" => "Yun Express",
            "ZEPTOEXPRESS" => "ZeptoExpress",
            "ZINC" => "Zinc",
            "ZJS_INTERNATIONAL" => "ZJS International",
            "ZTO_EXPRESS" => "ZTO Express",
            "ZYLLEM" => "Zyllem"
        ];
    }

    /**
     * Gets the region name from state code
     *
     * @param $stateId - State code
     * @return string
     */
    public function getRegionName($stateId)
    {
        try {
            $region = $this->regionFactory->create();
            return $region->load($stateId)['code'] ?? $stateId;
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        return $stateId;
    }

    /**
     * A map for popular US carriers with long titles
     *
     * @return array
     */
    protected function getSupplementaryCarriersMap()
    {
        return [
            'UPS'   => 'United Parcel Service',
            'USPS'  => 'United States Postal Service',
            'FEDEX' => 'Federal Express',
        ];
    }

    /**
     * @param $carrierTitle
     * @param array $carriersMap
     * @return string|false
     */
    protected function findCodeByTitle($carrierTitle, array $carriersMap)
    {
        foreach ($carriersMap as $code => $title) {
            if (stripos($carrierTitle, $title) !== false || stripos($carrierTitle, $code) !== false) {
                return $code;
            }
        }
        return false;
    }

    /**
     * @param Track $track
     * @return string
     */
    protected function getCanonicalCarrierCode($track)
    {
        $carrierCode = strtoupper($track->getCarrierCode());
        $carrierTitle = $track->getTitle();

        if ($carrierCode !== 'CUSTOM') {
            return $carrierCode;
        }

        $code = $this->findCodeByTitle($carrierTitle, $this->getSupplementaryCarriersMap());
        if ($code) {
            return $code;
        }
        $code = $this->findCodeByTitle($carrierTitle, $this->getFbSupportedShippingCarriers());
        if ($code) {
            return $code;
        }

        return 'OTHER';
    }

    /**
     * @param Track $track
     * @return string
     */
    public function getCarrierCodeForFacebook($track)
    {
        $supportedCarriers = $this->getFbSupportedShippingCarriers();
        $canonicalCarrierCode = $this->getCanonicalCarrierCode($track);

        return array_key_exists($canonicalCarrierCode, $supportedCarriers) ? $canonicalCarrierCode : 'OTHER';
    }
}
