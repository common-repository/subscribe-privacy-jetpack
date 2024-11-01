<?php
/**
 * Plugin Name:			Subscribe privacy for Jetpack
 * Description: 		Adds agreement for privacy policy to mail subscriptions. Needs the Jetpack plugin with the use of subscription form.
 * Version:				1.1.2
 * Requires at least:	4.9.6
 * Requires PHP:		7.0
 * Author:				alex6m
 * License:				GPL v2 or later
 * License URI:			https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:			subscribe-privacy-jetpack
 * Domain Path:			/languages
 */

add_action( 'init', 'subscribe_privacy_jetpack_load_textdomain' );

function subscribe_privacy_jetpack_load_textdomain() {
    load_plugin_textdomain( 'subscribe-privacy-jetpack', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}


function subscribePrivacy() {

?>
<script type="text/javascript">
// Ajout de l'accord obligatoire des règles de confidentialités (RGPD)
const subscribeForms = document.querySelectorAll("form[id^='subscribe-blog-']");

subscribeForms.forEach(function(jetpackSubscription) {
	// Nouveau paragraphe pour la case à cocher et son libellé
	let policy = document.createElement('p');
	policy.id = 'subscribe-policy';

	// Case à cocher obligatoire
	let subsPrivacyLinkCheckbox = document.createElement('input');
	subsPrivacyLinkCheckbox.type = 'checkbox';
	subsPrivacyLinkCheckbox.id = 'acceptpol';
	subsPrivacyLinkCheckbox.value = 1;
	subsPrivacyLinkCheckbox.checked = false;
	//subsPrivacyLinkCheckbox.required = true;
	subsPrivacyLinkCheckbox.name = 'Agree to our Privacy Policy';
	subsPrivacyLinkCheckbox.setCustomValidity('<?php _e('Please read and accept the terms of service and privacy policy', 'subscribe-privacy-jetpack'); ?>');

	// Libellé pour la case à cocher
	let labelpol = document.createElement('label');
	labelpol.htmlFor = 'acceptpol';
	labelpol.innerHTML = "<?php $policy_url_1 = '<a href=\"'.get_privacy_policy_url().'\" target=\"_blank\">';
								$policy_url_2 = '</a>&nbsp;&nbsp;';
								printf(__('I agree with %1$sterms of service and privacy policy%2$s', 'subscribe-privacy-jetpack'), $policy_url_1, $policy_url_2); ?>";

	// Ajout des éléments au formulaire
	policy.appendChild(subsPrivacyLinkCheckbox);
	policy.appendChild(labelpol);
	jetpackSubscription.insertBefore(policy, jetpackSubscription.querySelector('#subscribe-submit'));

	// Message d'erreur si case non cochée
	subsPrivacyLinkCheckbox.addEventListener('change', function (e) {
		if(subsPrivacyLinkCheckbox.validity.valueMissing || !subsPrivacyLinkCheckbox.checked) {
			subsPrivacyLinkCheckbox.setCustomValidity('<?php _e('Please read and accept the terms of service and privacy policy', 'subscribe-privacy-jetpack'); ?>');
		} else {
			subsPrivacyLinkCheckbox.setCustomValidity('');
		}
	});
});


</script>
<?php

}

if( class_exists('Jetpack') && Jetpack::is_module_active('subscriptions'))
	add_action('wp_footer', 'subscribePrivacy');
