<?php

namespace cookie\ui;



use n2n\impl\web\ui\view\html\HtmlView;
use bstmpl\model\BsTemplateModel;

class CookieHtmlBuilder {
	private $view;
	private $html;
	private $meta;
	public function __construct(HtmlView $view) {
		$this->view = $view;
		$this->html = $view->getHtmlBuilder();
		$this->meta = $this->html->meta();

		$this->meta->bodyEnd()->addJs('js/functions.js?v=' . BsTemplateModel::ASSETS_VERSION, 'cookie');
	}


	public function cookieConsentBanner(string $googleTagManagerId) {
		$this->view->out($this->getCookieConsentBanner($googleTagManagerId));
	}

	public function getCookieConsentBanner(string $googleTagManagerId) {
		return $this->view->getImport('\cookie\view\inc\consent.html', ['googleTagManagerId' => $googleTagManagerId]);
	}

	public function addCookieConsentJS(string $googleTagManagerId) {
		$this->meta->bodyEnd()->addJsCode("
			window.dataLayer = window.dataLayer || [];
			
			function gtag() {
			dataLayer.push(arguments);
			}
			
			if (localStorage.getItem('consentMode') === null) {
			gtag('consent', 'default', {
			'ad_storage': 'denied',
			'analytics_storage': 'denied',
			'personalization_storage': 'denied',
			'functionality_storage': 'denied',
			'security_storage': 'denied',
			});
			} else {
			gtag('consent', 'default', JSON.parse(localStorage.getItem('consentMode')));
			}
			
			if (localStorage.getItem('userId') != null) {
			window.dataLayer.push({
			'user_id': localStorage.getItem('userId')
			});
			}
			
			<!-- Google Tag Manager -->
			
			(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
			'gtm.start': new Date().getTime(),
			event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
			j = d.createElement(s),
			dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
			'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', '" . $googleTagManagerId . "');
			
			<!-- End Google Tag Manager -->"
		);
	}
}