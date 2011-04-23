<?php

/*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
* STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF 
* THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* http://www.opensource.org/licenses/bsd-license.php
*/

	require_once('admin.php');
	if (!defined('__FILE_OK')){
		die('Please login!');
	}

?><!DOCTYPE HTML>
<html>
<head>
	<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.5.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>
	<script src="js/knockout-1.2.0.debug.js"></script>
	<style type="text/css">
		body { blah: black }
		.prodName { width: 150px; font-size: 12px; }
		.itemPrice { width: 80px; }
		.shipping { width: 80px; }
		#uploadview { position: absolute; top: 5%; left: 5%;
					  width: 90%; height: 90%; background: white }
		#uploadview iframe { width: 90%; height: 90%; }
		#notify { position:fixed; top: 5px; left: 30%; padding:20px; font-size: 25px; background: #D4FFC9; }
	</style>
	<!-- By CodeJoust! -->
</head>
<body>

<div id="notify" data-bind='flash: notification, text: notification'>
	Loading...
</div>

<h3>Items</h3>
<div id="itemsList" data-bind='template: "itemsListTemplate"'> </div>

<hr />

<div>
	<h3>Configuration</h3>
	<div id="configview" data-bind='template: "config"'></div>
</div>
<!-- Config JS Tpls -->
<script type='text/html' id='config'>
	<table class="validate">
		<tr data-bind="template: { name: 'settingItemTpl', foreach: settings }"></tr>
	</table>
	<button data-bind="click: function(){ if (viewModel.sfilled()){ viewModel.saveSettings() } else { viewModel.notification('Fill in all required fields!'); } }">Save to Server<strong class="chg" data-bind="visible: viewModel.changed.settings"> *</strong></button>
</script>

<script type='text/html' id="settingItemTpl">
	<tr>
		<td><span data-bind="text: iname"></span>{{if req}}<span class="req">*</span>{{/if}}</td>
		<td><input class="{{if req}}required{{/if}}" type='text' data-bind="value: val, enable: viewModel.settingsLoaded" /></td>
	</tr>
</script>
<!-- end config -->

<div id="uploadview" data-bind="fadeVisible: viewopts.frameVisible">
	<button data-bind="click: close_frame">Close</button>
	<h2 data-bind="visible: viewopts.loading">Loading...</h2>
	<iframe src="about:blank" id="uploadFrame"></iframe>
</div>




<script type="text/html" id="itemsListTemplate">
	<table class='shoppingcart validate'>
		<thead>
			<tr>
				<th>Product Name & Price</th>
				<th>Shipping 1 & 2</th>
				<th>Product Description</th>
			</tr>
		</thead>
		<tbody>
			{{each(i, item) items()}}
				<tr>
					<td><input class="required prodName" data-bind="value: prodName" /></td>
					<td><abbr name="Shipping 1">s1:</abbr><input class="shipping number" data-bind="value: shipping1" /></td>
					<td rowspan="2">
						<textarea rows='3' cols='30' data-bind="value: prodDesc"></textarea>
					</td>
					<td valign='top' rowspan="2">
						{{if imgid}}<img height="60" data-bind="attr: {src: viewModel.get_url(imgid)}, click: function(){ viewModel.uploadImg(item) }" />{{else}}<a href="#" data-bind="click: function(){ viewModel.uploadImg(item) }">Upload</a>{{/if}}
					</td>
					<td valign='top'>
						<a href="#" data-bind='click: function(){ confirm("Are you sure?") && viewModel.removeItem(item); }'>Delete</a>
					</td>
				</tr>
				<tr>
					<td valign="top">&#36;<input class='itemPrice number' data-bind="value: prodPrice" /> <span>Sizes:</span><input type="checkbox" data-bind="checked: sizes" /></td>
					<td valign="top"><abbr name="Shipping 2">s2:</abbr><input class='shipping number' data-bind="value: shipping2" /></td>
				</tr>
			{{/each}}
		</tbody>
	</table>
	<div class="actions">
		<button data-bind="click: addItem, enable: settingsLoaded()">Add an Item</button>
		<button data-bind="click: saveItems, enable: items().length > 0">Save Items to Server<strong class="chg" data-bind="visible: viewModel.changed.settings"> *</strong></button>
		<button data-bind="click: revertItems">Revert to last save</button>
	</div>
</script>

		<script type="text/javascript">
		function uniqid(prefix, more_entropy) {
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kankrelune (http://www.webfaktory.info/)
    // %        note 1: Uses an internal counter (in php_js global) to avoid collision
    // *     example 1: uniqid();
    // *     returns 1: 'a30285b160c14'
    // *     example 2: uniqid('foo');
    // *     returns 2: 'fooa30285b1cd361'
    // *     example 3: uniqid('bar', true);
    // *     returns 3: 'bara20285b23dfd1.31879087'
    if (typeof prefix == 'undefined') { prefix = ""; }
    var retId; var formatSeed = function(seed, reqWidth) {
        seed = parseInt(seed, 10).toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        } if (reqWidth > seed.length) { // so short we pad
            return Array(1 + (reqWidth - seed.length)).join('0') + seed;
        } return seed; };
    // BEGIN REDUNDANT
    if (!this.php_js) { this.php_js = {}; }
    // END REDUNDANT
    if (!this.php_js.uniqidSeed) { // init seed with big random int
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    this.php_js.uniqidSeed++;
    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10).toFixed(8).toString(); }
    return retId; }
	// Here's a custom Knockout binding that makes elements shown/hidden via jQuery's fadeIn()/fadeOut() methods
// Could be stored in a separate utility library
ko.bindingHandlers.fadeVisible = {
    init: function (element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        $(element).toggle(ko.utils.unwrapObservable(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function (element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.utils.unwrapObservable(value) ? $(element).fadeIn() : $(element).fadeOut();
    }
};	
ko.bindingHandlers.flash = {
    init: function (element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        $(element).toggle(ko.utils.unwrapObservable(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function (element, valueAccessor) {
    	var goin = function(el){
    		el.fadeIn();
    		setTimeout(function(){ el.fadeOut() }, 2000); 
    	}
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.utils.unwrapObservable(value) ? goin($(element)) : $(element).fadeOut();
    }
};	
	</script>
	
	<script type="text/javascript">
		
		var viewModel = {
			servData: '',
			notification: new ko.observable(''),
			items: new ko.observableArray([]),
			changed: new ko.observable(false),
			itemsLoaded: new ko.observable(false),
			settingsLoaded: new ko.observable(false),
			viewopts: { frameVisible: new ko.observable(false), loading: new ko.observable(false) },
			changed: { settings: new ko.observable(false), items: new ko.observable(false) },
			settings: new ko.observableArray([
				{key: 'paypal', iname:'Paypal Email', req: true, val: 'loading...'},
				{key: 'gkey', iname:'Google Merchant Id', req: false, val: ''},
				{key: 'surl', iname:'Site URL', req: true, val: ''},
				{key: 'contactemail', iname:'Contact Email', req: true, val: ''},
				{key: 'thxpg', iname:'Thank You Page URL', req: false, val: ''},
				{key: 'currency', iname: 'Currency Code', req: true, val: 'USD'}]),
			sizes: new ko.observableArray([
				'Mens Small', 'Mens Medium', 'Mens Large', 'Mens Extra Large', 'Womens Small', 'Womens Large', 'Womens Extra Large'
			]),
			removeItem: function(item){
				if (item.imgid != '' && item.imgid != undefined){
					$.post('upload_crop.php?num='+item.imgid, {'a': 'delete', 'num': item.imgid, 'ajax': 'true'}, function(res){
						// donothing.
					});
				}
				viewModel.items.remove(item);
			},
			addItem: function(){
				viewModel.items.push({prodName: '', prodPrice: 0.0, sizes: false, prodDesc: '', shipping1: 0.00, shipping2: 0.00})
			},
			revertItems: function(){
				viewModel.updateServer();
			},
			get_url: function(path){
				return '<?php echo $imgpath; ?>/thumb_'+path+'.jpg';
			},
			notify_updated: function(){
				viewModel.items.valueHasMutated();
			},
			uploadImg: function(item){
				var iid = uniqid();
				if (item.imgid == undefined || item.imgid == ''){
					item.imgid = iid;
				} else {
					iid = item.imgid;
				}
				viewModel.items.valueHasMutated();
				viewModel.viewopts.frameVisible(true);
				$('#uploadFrame').attr('src', 'upload_crop.php?num=' + iid).load(function(){
					viewModel.viewopts.loading(false);
				});
			},
			saveItems: function(){
				viewModel.saveServ({items:ko.utils.stringifyJson(this.items)});
			},
			saveSettings: function(){
				viewModel.saveServ({settings:ko.utils.stringifyJson(this.settings)});
			},
			saveServ: function(data){
				data['save'] = true;
				$.post('savecart.php', data, function(data){
					if ('success' in data && data['success'] == true){ viewModel.notification('saved successfully!') }
					viewModel.updateServer();
				}, 'json');
			},
			sfilled: function(){
				var settings = viewModel.settings();
				for (setting in settings){
					if (settings[setting].req && settings[setting].val == ''){
						return false;
					}
				}
				return true;
			},
			close_frame: function(){
				viewModel.viewopts.frameVisible(false);
			},
			updateServer: function(){
				$.getJSON('data/data.json', function(data){
					if ('settings' in data){
						viewModel.settings(data['settings']);
						viewModel.settingsLoaded(true);
						viewModel.changed.settings(false);
					}
					if ('items' in data){
						viewModel.items(data['items']);
						viewModel.itemsLoaded(true);
						viewModel.changed.items(false);
					}
					viewModel.servData = JSON.stringify({items: viewModel.items(), settings: viewModel.settings()});
				});
			},
			haschanged: function(){
				var localData = JSON.stringify({items: viewModel.items(), settings: viewModel.settings()});
				return (localData != viewModel.servData);
			}
		};
		$(function(){
			window.onbeforeunload = function(){ 
				if (viewModel.haschanged()){ 
					viewModel.changed.settings(true);
					viewModel.changed.items(true);
					return "Are you sure you want to quit before you save your changes to the server?";
				}
			};
			window.imageUpdated = viewModel.notify_updated;
			window.closeFrame = viewModel.close_frame;
			viewModel.updateServer();
			ko.applyBindings(viewModel);
		});
	</script>
	
<hr />
	
	<div>
	built by <a href='http://codejoust.com'>codejoust!</a>
	</div>
	
</body>
</html>
