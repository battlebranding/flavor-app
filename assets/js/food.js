/**
 * Mobile Order Food
 */

window.MobileOrderFood = {};

( function( window, app ) {

	'use strict';

	// The cache object.
	var c = {};

	// Constructor.
	app.init = function() {
		app.cache();
		app.bindEvents();
	};

	// Cache selectors.
	app.cache = function() {
		c.toppings 				= document.querySelectorAll( '.topping' );
		c.includedToppingsList 	= document.querySelector( '#toppings-list' );
		c.extraToppingsList 	= document.querySelector( '#extra-toppings-list' );
	};

	// Combine all events.
	app.bindEvents = function() {

		// Attach listeners to each .topping
		if ( c.toppings ) {

			c.toppings.forEach( function( topping ) {
				topping.addEventListener( 'click', app.applyTopping );
			});

		}

	};

	app.applyTopping = function( click_event ) {

		click_event.preventDefault();

		// Get the parent list
		var topping 				= click_event.target.closest('.topping');
		var toppingList 			= topping.closest('ul');
		var alternateToppingList 	= document.querySelector( '#' + toppingList.dataset.alternateList );

		// var alternateToppingList =
		console.log( alternateToppingList );
		toppingList.removeChild( topping.parentNode );
		alternateToppingList.appendChild( topping.parentNode );
				
	}


	// Engage!
	app.init();

})( window, window.MobileOrderFood );
