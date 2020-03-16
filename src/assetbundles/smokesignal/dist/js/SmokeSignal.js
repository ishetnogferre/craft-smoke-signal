/**
 * Smoke Signal plugin for Craft CMS
 *
 * Smoke Signal JS
 *
 * @author    Marbles
 * @copyright Copyright (c) 2020 Marbles
 * @link      https://www.marbles.be/
 * @package   SmokeSignal
 * @since     1.0.0
 */

 document.getElementById('linkSelect').addEventListener('change', function () {
     console.log(this.value);
     var item = document.getElementById(this.value);
     var old = document.getElementById(this.value == 'entry' ? 'url' : 'entry' );

     item.classList.remove('hidden');
     item.getElementsByTagName('input')[0].removeAttribute('disabled');
     old.classList.add('hidden');
     old.getElementsByTagName('input')[0].setAttribute('disabled', true);;
 });


(function () {
    document.getElementById('close-btn').addEventListener('click', function (e) {
        document.getElementsByClassName('signal')[0].remove();
        e.preventDefault();
    });
})();
