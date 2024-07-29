<?php

	use n2n\impl\web\ui\view\html\HtmlView;
	use bstmpl\model\BsTemplateModel;
	use n2n\web\http\nav\Murl;
	use page\model\nav\murl\MurlPage;
use dbtext\DbtextHtmlBuilder;

	$view = HtmlView::view($view);
	$html = HtmlView::html($view);
	$meta = $html->meta();

	$dbText = new DbtextHtmlBuilder($view);


	$hasPrivacyPage = $view->buildUrl(MurlPage::hook(BsTemplateModel::HOOK_PAGE_PRIVACY), false);
	$hasImprintPage = $view->buildUrl(MurlPage::hook(BsTemplateModel::HOOK_PAGE_IMPRINT), false);

	$googleTagManagerId = $view->getParam('googleTagManagerId', false, null);

	if (!$googleTagManagerId) return;
?>

<dialog id="cookie-consent-banner" class="cookie-consent-banner" data-opener-ref="cookie-consent-opener">
	<h2 class="h4"><?php $dbText->t('cookies_title') ?></h2>
	<p><?php $dbText->t('cookies_intro_msg') ?></p>

	<div class="cookie-consent-options">
		<div class="cookie-consent-option">
			<div class="cookie-consent-option-opener">
				<label><input id="consent-analytics" type="checkbox" value="analytics" checked><?php $dbText->t('cookies_analytics_label') ?></label>
				<button class="cookie-consent-option-opener-trigger">
					<i class="cookie-consent-option-opener-trigger-icon"></i>
				</button>
			</div>
			<div class="cookie-consent-option-content">
				<?php $dbText->t('cookies_analytics_infotext_msg') ?>
			</div>
		</div>
		<div class="cookie-consent-option">
			<div class="cookie-consent-option-opener">
				<label><input id="consent-preferences" type="checkbox" value="preferences" checked><?php $dbText->t('cookies_preferences_label') ?></label>
				<button class="cookie-consent-option-opener-trigger">
					<i class="cookie-consent-option-opener-trigger-icon"></i>
				</button>
			</div>
			<div class="cookie-consent-option-content">
				<?php $dbText->t('cookies_preferences_infotext_msg') ?>
			</div>
		</div>
		<div class="cookie-consent-option">
			<div class="cookie-consent-option-opener">
				<label><input id="consent-marketing" type="checkbox" value="marketing"><?php $dbText->t('cookies_marketing_label') ?></label>
				<button class="cookie-consent-option-opener-trigger">
					<i class="cookie-consent-option-opener-trigger-icon"></i>
				</button>
			</div>
			<div class="cookie-consent-option-content">
				<?php $dbText->t('cookies_marketing_infotext_msg') ?>
			</div>
		</div>
		<div class="cookie-consent-option">
			<div class="cookie-consent-option-opener">
				<label><input id="consent-necessary" type="checkbox" value="necessary" checked disabled><?php $dbText->t('cookies_necessary_label') ?></label>
				<button class="cookie-consent-option-opener-trigger">
					<i class="cookie-consent-option-opener-trigger-icon"></i>
				</button>
			</div>
			<div class="cookie-consent-option-content">
				<?php $dbText->t('cookies_necessary_infotext_msg') ?>
			</div>
		</div>
	</div>

	<div class="cookie-consent-btn-group">
		<button id="btn-accept-all" class="cookie-consent-button btn btn-sm btn-primary"><?php $dbText->t('cookies_accept_all_label') ?></button>
		<button id="btn-reject-all" class="cookie-consent-button btn btn-sm btn-outline-primary"><?php $dbText->t('cookies_reject_all_label') ?></button>
		<button id="btn-accept-some" class="cookie-consent-button btn btn-sm btn-outline-primary"><?php $dbText->t('cookies_accept_selection_label') ?></button>
	</div>
	<?php if ($hasPrivacyPage || $hasImprintPage): ?>
		<div class="cookie-consent-footer">
			<nav class="nav nav-inline cookie-consent-nav">
				<ul class="nav nav-inline">
					<?php if ($hasPrivacyPage): ?>
						<li><?php $html->link(MurlPage::hook(BsTemplateModel::HOOK_PAGE_PRIVACY)) ?></li>
					<?php endif ?>
					<?php if ($hasImprintPage): ?>
						<li><?php $html->link(MurlPage::hook(BsTemplateModel::HOOK_PAGE_IMPRINT)) ?></li>
					<?php endif ?>
				</ul>
			</nav>
		</div>
	<?php endif ?>
</dialog>

<button aria-label="Open Privacy-Preferences" id="cookie-consent-opener" class="cookie-consent-opener btn btn-dark">
	<span aria-hidden="true" class="">
		<svg xmlns="http://www.w3.org/2000/svg" height="100%" viewBox="0 0 24 24" width="100%" fill="currentColor" aria-hidden="true"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11.19 1.36l-7 3.11C3.47 4.79 3 5.51 3 6.3V11c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V6.3c0-.79-.47-1.51-1.19-1.83l-7-3.11c-.51-.23-1.11-.23-1.62 0zm-1.9 14.93L6.7 13.7c-.39-.39-.39-1.02 0-1.41.39-.39 1.02-.39 1.41 0L10 14.17l5.88-5.88c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41l-6.59 6.59c-.38.39-1.02.39-1.41 0z"></path></svg>
	</span>
</button>
