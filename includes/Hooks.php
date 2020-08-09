<?php

namespace MediaWiki\Extension\DarkMode;

use User;
use OutputPage;
use Skin;
use SkinTemplate;
use Title;
use MediaWiki\MediaWikiServices;

class Hooks {
	/**
	 * Handler for PersonalUrls hook.
	 * Add a "Dark mode" item to the user toolbar ('personal URLs').
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/PersonalUrls
	 * @param array &$personal_urls Array of URLs to append to.
	 * @param Title &$title Title of page being visited.
	 * @param SkinTemplate $skin
	 */
	public static function onPersonalUrls( array &$personal_urls, Title &$title, SkinTemplate $skin ) {
		if ( !self::shouldHaveDarkMode( $skin ) ) {
			return;
		}

		$messageKey = self::shouldHaveDarkModeEnabledOnLoad( $skin )
			? 'darkmode-default-link' : 'darkmode-link';

		$after = $skin->getUser()->isLoggedIn()
			? 'mytalk' : 'anontalk';

		$insertUrls = [
			'darkmode-link' => [
				'text' => $skin->msg( $messageKey )->text(),
				'href' => '#',
				'active' => false,
			]
		];

		if (array_key_exists($after, $personal_urls)) {
			$personal_urls = wfArrayInsertAfter( $personal_urls, $insertUrls, $after );
		} else {
			// most likely no permissions to edit or something, let's just append to the beginning
			$personal_urls = $insertUrls + $personal_urls;
		}
	}

	/**
	 * Handler for BeforePageDisplay hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 * @param OutputPage $output
	 * @param Skin $skin Skin being used.
	 */
	public static function onBeforePageDisplay( OutputPage $output, Skin $skin ) {
		if ( !self::shouldHaveDarkMode( $skin ) ) {
			return;
		}

		$output->addModules( 'ext.DarkMode' );
		$output->addModuleStyles( 'ext.DarkMode.styles' );

		if ( self::shouldHaveDarkModeEnabledOnLoad( $skin ) ) {
			$output->addHtmlClasses( 'client-dark-mode' );
		}
	}

	/**
	 * Handler for GetPreferences hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetPreferences
	 * @param User $user
	 * @param array $preferences
	 */
	public static function onGetPreferences( User $user, array &$preferences )
	{
		$preferences['darkmode-enabled'] = [
			'type' => 'api',
			'default' => 1,
		];
	}

	/**
	 * Conditions for when Dark Mode should be available.
	 * @param Skin $skin
	 * @return bool
	 */
	private static function shouldHaveDarkMode( Skin $skin ) {
		return $skin->getSkinName() !== 'minerva' && $skin->getSkinName() !== 'peruna';
	}

	/**
	 * Conditions for when Dark Mode should be enabled when a page is loaded.
	 * @param Skin $skin
	 * @return bool
	 */
	private static function shouldHaveDarkModeEnabledOnLoad( Skin $skin ) {
		return MediaWikiServices::getInstance()->getUserOptionsLookup()
			->getOption( $skin->getUser(), 'darkmode-enabled' );
	}
}
