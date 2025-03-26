jQuery(document).ready(function($) {
	(function() {


		var jqElemCookieConsentBanner = $(".cookie-consent-banner");

		if (jqElemCookieConsentBanner.length === 0) return;
		if (jqElemCookieConsentBanner.length > 1) {
			console.warn("More than one cookie consent banner found: Found " + jqElemCookieConsentBanner.length + " items. Only first element will be used!");
		}

		function setConsent(consent) {
			const consentMode = {
				"marketing": consent.marketing ? "granted" : "denied",
				"analytics": consent.analytics ? "granted" : "denied",
				"preferences": consent.preferences ? "granted" : "denied",
				"functionality_storage": consent.necessary ? "granted" : "denied",
				"security_storage": consent.necessary ? "granted" : "denied",
				"ad_storage": consent.marketing ? "granted" : "denied",
				"ad_user_data": consent.marketing ? "granted" : "denied",
				"ad_personalization": consent.marketing ? "granted" : "denied",
				"analytics_storage": consent.analytics ? "granted" : "denied",
				"personalization_storage": consent.preferences ? "granted" : "denied",
			};
			gtag("consent", "update", consentMode);
			localStorage.setItem("consentMode", JSON.stringify(consentMode));
		}

		var CookieConsentBanner = function(jqElem) {
			this.jqElem = jqElem;
			this.btnAcceptAll = jqElem.find("#btn-accept-all");
			this.btnAcceptSome = jqElem.find("#btn-accept-some");
			this.btnAcceptNone = jqElem.find("#btn-reject-all");
			this.opener = $("#" + jqElem.data("opener-ref"));
			this.btnClose = jqElem.find(".cookie-consent-close");
			this.jqElemsOptions = jqElem.find(".cookie-consent-option");
			var that = this;

			this.init();


			this.jqElemsOptions.each(function() {
				new CookieConsentOptionItem($(this));
			});

			this.btnClose.on("click", function() {
				that.hide();
			});

			this.opener.on("click", function() {
				that.show();
			});

			this.jqElem.on('cancel', (event) => {
				that.hide();
			});

			this.jqElem.on("click", (event) => {
				var rect = this.jqElem[0].getBoundingClientRect();
				var isInDialog = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height &&
						rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
				if (!isInDialog) {
					that.hide();
				}
			})


			this.btnAcceptAll.on("click", () => {
				setConsent({
					necessary: true,
					analytics: true,
					preferences: true,
					marketing: true
				});

				$("#consent-analytics").prop( "checked", 1);
				$("#consent-preferences").prop( "checked", 1);
				$("#consent-marketing").prop( "checked", 1);
				that.hide();
			});

			this.btnAcceptSome.on("click", function() {
				setConsent({
					necessary: true,
					analytics: document.getElementById("consent-analytics").checked,
					preferences: document.getElementById("consent-preferences").checked,
					marketing: document.getElementById("consent-marketing").checked
				});
				that.hide();
			});

			this.btnAcceptNone.on("click", function() {
				setConsent({
					necessary: false,
					analytics: false,
					preferences: false,
					marketing: false
				});

				$("#consent-analytics").prop( "checked", 0);
				$("#consent-preferences").prop( "checked", 0);
				$("#consent-marketing").prop( "checked", 0);
				that.hide();
			});

			if (localStorage.getItem("consentMode") === null) {
				that.show();
			}
		};

		CookieConsentBanner.prototype.init = function() {
			that = this;
			if (localStorage.getItem('consentMode') !== null) {
				var parsedCode = JSON.parse(localStorage.getItem('consentMode'));

				// console.log(parsedCode);

				this.jqElemsOptions.each(function() {
					var option = $(this);
					var optionInput = option.find("input:first");
					var key = optionInput.val().toLowerCase();
					var value = parsedCode[key];
					that.updateOptionsInput(key, value);
				});

			}
		}

		CookieConsentBanner.prototype.hide = function() {
			this.hideBanner();
			this.showOpener();
		}

		CookieConsentBanner.prototype.show = function() {
			this.showBanner();
			this.hideOpener();
		}

		CookieConsentBanner.prototype.hideBanner = function() {
			this.jqElem[0].close();
		}

		CookieConsentBanner.prototype.showBanner = function() {
			this.init();
			this.jqElem[0].showModal();
		}

		CookieConsentBanner.prototype.hideOpener = function() {
			this.opener.hide();
		}

		CookieConsentBanner.prototype.showOpener = function() {
			this.opener.show();
		}

		CookieConsentBanner.prototype.updateOptionsInput = function(key, value) {
			if ([undefined, 'denied', 'granted'].indexOf(value) === -1) {
				console.warn('value not allowed: ' + value);
				return;
			}
			this.jqElem.find("#consent-" + key).prop("checked", (value === 'denied') ? '' : 1);
		}


		var CookieConsentOptionItem = function(jqElem) {
			this.jqElem = jqElem;
			this.jqElemToToggle = jqElem.find(".cookie-consent-option-content:first").hide();
			this.jqElemTrigger = jqElem.find(".cookie-consent-option-opener-trigger:first");
			this.isOpen = false;
			this.triggerOpenClass = "open";

			var that = this;

			this.jqElemTrigger.on("click", () => {
				that.toggle();
			});
		}


		CookieConsentOptionItem.prototype.toggle = function() {
			if (this.isOpen) {
				this.jqElemToToggle.slideUp("fast");
				this.jqElemTrigger.removeClass(this.triggerOpenClass);
				this.isOpen = false;
			} else {
				this.jqElemToToggle.slideDown("fast");
				this.jqElemTrigger.addClass(this.triggerOpenClass);
				this.isOpen = true;
			}
		};

		new CookieConsentBanner(jqElemCookieConsentBanner.first());

	})();
});
