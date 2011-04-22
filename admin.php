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

// Main Configuration

$imgpath = 'upload_pic/'; // Folder needs write-by-all permissions.
$uname = 'testing';
$password = 'dc724af18fbdd4e59189f5fe768a5f8311527050';

// to set password: goto admin.php?do=genpass then paste the resulting text into the $password variable //
// to logout: goto admin.php?do=logout

session_start();

function logout(){
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]); }
	session_destroy();
}

function check_login(){
	global $uname;
	global $pass;
	if (isset($_SESSION['uname']) && isset($_SESSION['key'])){
		if ($_SESSION['uname'] === $uname && sha1($uname . $pass) === $_SESSION['key']){
			define('__FILE_OK', true);
			return true;
		}
	}
	return false;
}

function login($text = null){ ?>
	<h3>Login to the CMS admin</h3>
	<?php if ($text != null){ echo '<h5>'.$text.'</h5>'; } ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?do=login" method="POST">
		<p><label>Username: <input type="text" name="uname" /></label></p>
		<p><label>Password: <input type="password" name="pass" /></p>
		<p><input type="submit" value="Login" /></p>
	</form>
<?php }

if (isset($_GET['do'])){
	if ($_GET['do'] == 'genpass'){
		if (!isset($_POST['pass'])){
			echo '<form action="'.$_SERVER['PHP_SELF'].'?do=genpass" method="post">Password: <input type="text" name="pass" /><input type="submit" /></form>';
		} else {
			echo '<p>Your password is below - copy and paste that into the top of admin.php</p>';
			echo '<textarea onclick="this.focus;this.select">'.sha1($_POST['pass']).'</textarea>';
		}
	} else if ($_GET['do'] == 'logout'){
		logout();
		echo 'Logged off!';
	} else if ($_GET['do'] == 'login'){
		if (!isset($_POST['pass']) && !isset($_POST['uname'])){
			login();
		} else {
			if (sha1($_POST['pass']) === $password && $_POST['uname'] === $uname){
				$_SESSION['uname'] = $uname;
				$_SESSION['key'] = sha1($uname . $pass);
				echo 'Successfully Logged in!';
				header('Location: '.dirname($_SERVER['PHP_SELF']));
			} else {
				login('Login Failed!');
			}
		}
	} else { echo 'No Admin Action Specified.'; }
	exit();
}

if (!check_login()){
	login();
	exit();
}