<?
/*--------------------------------
	Developed by Andrey Bazykin (AB)
	Site: http://andreybazykin.com
	Skype: skaterfat
	E-mail: andreybazykin@gmail.com
	~=Development of Dreams=~
-------------------------------*/

use Bitrix\Main\EventManager,
    Bitrix\Main\Loader,
    Bitrix\Sale,
    Bitrix\Main\Context;

EventManager::getInstance()->addEventHandler('sale', 'OnOrderNewSendEmail', ['AZIMUT', 'OnOrderNewSendEmailHandler']);

class AZIMUT {

	public static function OnOrderNewSendEmailHandler($ID, &$eventName, &$arFields) {

		$arFields['CONTACTS_INFO'] = "";

		if($ID > 0) {

			$order = Sale\Order::load($ID);

			$propertyCollection = $order->getPropertyCollection()->getArray();

			$arContacts = array();

			foreach($propertyCollection['properties'] as $prop) {
				if($prop['CODE'] == 'FIO' || $prop['CODE'] == 'CONTACT_PERSON')
					$arContacts[] = "Имя: <strong>" . $prop['VALUE'][0] . "</strong>";
				if($prop['CODE'] == 'EMAIL')
					$arContacts[] = "Email: <strong>" . $prop['VALUE'][0] . "</strong>";
				if($prop['CODE'] == 'PHONE')
					$arContacts[] = "Телефон: <strong>" . $prop['VALUE'][0] . "</strong>";
			}

			$arFields['CONTACTS_INFO'] = implode("<br>", $arContacts);

		}

	}

}


?>