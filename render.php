<?php /** CodeJoust **/

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

/*
// Usage Demo: (on store.php, etc)
<?php require_once 'admin/render.php'; ?> // Put this at the top of the file.
<?php show_items(); ?> // Prints out items html as specified in earlier template.
<?php view_cart_button(); ?> // Prints out the view cart button
*/

$data = json_decode(file_get_contents('data/data.json'));

$config = array();
foreach ($data->settings as $prop){
	$config[$prop->key] = $prop->val;
}


function render_input($name, $val){
	return "<input type='hidden' name='$name' value='$val' />\n";
}
function render_inputs($arr){
	$buf = '';
	foreach ($arr as $name => $val){
		$buf = $buf . render_input($name, $val);
	}
	return $buf;
}

function view_cart_button(){
global $config;
global $data;

?>

<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">  
 <input type="hidden" name="business" value="<?php echo $config['contactemail']; ?>">  
 <input type="hidden" name="cmd" value="_cart">  
 <input type="hidden" name="display" value="1">   
 <input type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_viewcart_LG.gif" alt="PayPal - The safer, easier way to pay online" />  
 <img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" />  
</form>

<?php }

function show_items(){
global $config;
global $imgpath;
global $data;
$default_config = array('currency_code' => $config['currency'], 'shopping_url'=>$config['surl'],
						'cmd'=>'_cart', 'add'=>'1', 'business'=>$config['paypal']);

?>

<table cellpadding="5" border="0" class='products'>

<?php foreach ($data->items as $item): ?>
<tr class='item'>
	
	<td valign='top'>
		<a href="<?php echo $imgpath; ?>/large_<?php echo $item->imgid; ?>.jpg" target="_self" rel="lightbox">
			<?php if ($item->imgid != ''): ?>
				<img src='<?php echo $imgpath; ?>/_<?php echo $item->imgid; ?>.jpg' />
			<?php endif; ?>
			<div style="width: 100px; position:relative;"></div>
		</a>
	</td>
	<td>
		<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<p><strong>Product Name: </strong> <?php echo $item->prodName; ?></p>
		<p><strong>Product Description: </strong> <?php echo $item->prodDesc; ?></p>
		<p><strong>Product Price: </strong> <?php echo $item->prodPrice; ?></p>
		<p><strong>Quantity: </strong><input name="quantity" type="text" value="1" size="12" /></p>
	
		<?php if ($item->sizes): ?>
		<p><strong>Size: </strong>
			<input type="hidden" name="on0" value="Size"> 
              <select name="os0"> 
                <option value="Mens Small">Mens Small</option> 
                <option value="Mens Medium">Mens Medium</option> 
                <option value="Mens Large">Mens Large</option> 
                <option value="Mens Extra Large">Mens Extra Large</option> 
                <option value="Womens Small">Womens Small</option> 
                <option value="Womens Medium">Womens Medium</option> 
                <option value="Womens Large">Womens Large</option> 
                <option value="Womens Extra Large">Womens Extra Large</option> 
              </select> 
		</p>
		<?php endif; ?>
	
		<?php echo render_inputs(array_merge($default_config, array('shipping'=>$item->shipping1, 'shipping2'=>$item->shipping2,
																	'item_name'=>$item->prodName, 'amount'=>$item->prodPrice)));  ?>	
		
		<input type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_cart_LG.gif" alt="PayPal - The safer, easier way to pay online">  
 		<img alt="px" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" />
		</form>
	</td>
	<tr><td colspan="2"><hr /></td></tr>
</tr>
<?php endforeach;
} // end show items function.


// Demonstration:
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])){
	view_cart_button();
	show_items();
}
