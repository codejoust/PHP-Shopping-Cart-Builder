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

/** CodeJoust! **/

require_once('admin.php');
if (!defined('__FILE_OK')){
	die('Please login!');
}

$data = json_decode(file_get_contents('data/data.json'));
$res = array('success'=>false,'updated_items'=>false,'updated_settings'=>false);

if (isset($_POST['save'])){
	if (isset($_POST['items'])){
		$data->items = json_decode($_POST['items']);
		$res['updated_items'] = true;
	}
	if (isset($_POST['settings'])){
		$data->settings = json_decode($_POST['settings']);
		$res['updated_settings'] = true;
	}
	copy('data/data.json', 'data/data-bk.json');
	file_put_contents('data/data.json', json_encode($data));
	
	$res['success'] = true;
}

die(json_encode($res));