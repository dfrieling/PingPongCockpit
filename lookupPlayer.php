<?php

require_once (__DIR__ . '/src/Model/NullPlayer.php');
require_once (__DIR__ . '/src/Model/PlayerMysql.php');
require_once(__DIR__ . '/src/PlayerImageWinning.php');
require_once(__DIR__ . '/src/PlayerImagePlaying.php');
require_once(__DIR__ . '/src/FileTransfer/SshTransfer.php');

use Model\NullPlayer;
use Model\Player;
use Model\PlayerMysql;

const MESSAGE_LEVEL_ERROR = 'danger';
const MESSAGE_LEVEL_SUCCESS = 'success';
const MESSAGE_LEVEL_WARNING = 'warning';
const MESSAGE_LEVEL_INFO = 'info';

$message = [];
$player = null;

updatePlayer();
uploadImages();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css"/>
    <script type="application/javascript">
        $(document).on('click', '.browse', function(){
            var file = $(this).parent().parent().parent().find('.file');
            file.trigger('click');
        });
        $(document).on('change', '.file', function(){
            $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });
    </script>
</head>
<body>
<div class="container col-md-4 col-md-offset-3">
    <div class="row">
        <div id="message-danger" class="alert alert-danger" style="display:none;"></div>
        <div id="message-warning" class="alert alert-warning" style="display:none;"></div>
        <div id="message-success" class="alert alert-success" style="display:none;"></div>
        <div id="message-info" class="alert alert-info" style="display:none;"></div>
    </div>
    <div class="row">
        <h3>
            User Settings
        </h3>
        <form class="form-horizontal" action="lookupPlayer.php?rfid=<?php echo getPlayer()->getRfid(); ?>" method="POST"
              enctype="multipart/form-data">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="name">Chip number</label>
                    <div class="controls">
                        <input type="text" id="rfid" name="rfid" placeholder="" readonly
                               value="<?php echo getPlayer()->getRfid(); ?>" class="form-control input-lg">
                        <small class="form-text text-muted">The number of your RFID chip</small>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input type="text" id="username" name="name" placeholder=""
                               value="<?php echo getPlayer()->getName(); ?>" class="form-control input-lg">
                        <small class="form-text text-muted">Username can contain any letters or numbers
                        </small>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Gender:</label>
                    <p style="margin: 0px;">
                        <input type="radio" name="gender" id="female"
                               value="female" <?php if (empty(getPlayer()->getGender()) || getPlayer()->getGender() == 'female') echo 'checked' ?>>
                        Female
                        <input type="radio" name="gender" id="male"
                               value="male" <?php if (getPlayer()->getGender() == 'male') echo 'checked' ?>> Male
                    </p>
                    <small style="padding: 0px;" class="form-text text-muted">The gender will be user for genderized
                        user interface messages
                    </small>
                </div>
                <div class="control-group">
                    <label class="control-label">User Picture When Playing:</label>
                    <div>
                        <?php
                        $fileContents = getPlayerImagePlaying(getPlayer());
                        if (!empty($fileContents)) {
                            echo sprintf('<img class="img-responsive img-thumbnail" style="max-width: 50%%; margin-bottom: 0.5em;" src="data:image/png;base64,%s" />',
                                base64_encode($fileContents));
                        }
                        ?>
                    </div>
                    <input type="file" class="file" name="imgPlaying" style="display: none;">
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="fa fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i
                                        class="glyphicon glyphicon-search"></i> Browse</button>
                        </span>
                    </div>
                    <small class="form-text text-muted">The image will be used as your avatar when playing.</small>
                </div>
                <div class="control-group">
                    <label class="control-label">User Picture When Winning:</label>
                    <div>
                        <?php
                        $fileContents = getPlayerImageWinning(getPlayer());
                        if (!empty($fileContents)) {
                            echo sprintf('<img class="img-responsive img-thumbnail" style="max-width: 50%%; margin-bottom: 0.5em;" src="data:image/png;base64,%s" />',
                                base64_encode($fileContents));
                        }
                        ?>
                    </div>
                    <input type="file" class="file" name="imgWinning" style="display: none;">
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="fa fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i
                                        class="glyphicon glyphicon-search"></i> Browse</button>
                        </span>
                    </div>
                    <small class="form-text text-muted">The image will be used as your avatar when you won.</small>
                </div>
                <div class="control-group" style="margin-top: 1.5em;">
                    <div class="controls">
                        <button type="submit" name="SavePlayerSettings" class="btn btn-success">Save</button>
                    </div>
                </div>
                <div class="row"></div>
            </fieldset>
        </form>
    </div>
<?php
foreach ($GLOBALS['message'] as $level => $message) {
    $display = str_replace("'", "\'", "<strong>" . ucfirst($level) . "!</strong> " . ucfirst($message));
    echo "
            <script type=\"application/javascript\">
                document.getElementById('message-$level').innerHTML = '$display';
                document.getElementById('message-$level').style.display = '';
            </script>
        ";
}
?>
</body>
</html>
<?php

/**
 * @return Player
 */
function getPlayer()
{
    if ($GLOBALS['player']) {
        return $GLOBALS['player'];
    }

    $player = new NullPlayer();

    if (empty($_REQUEST['rfid'])) {
        addUserMessage(MESSAGE_LEVEL_ERROR, 'no user specified, redirecting back to the login page');
        header("refresh:5;url=index.php");
        return $player;
    }

    try {
        $player = (new PlayerMysql())->getExistingOrNewFromRfid($_REQUEST['rfid']);
    } catch (\Exception $e) {
        addUserMessage(MESSAGE_LEVEL_ERROR, $e->getMessage());
    }
    $GLOBALS['player'] = $player;

    return $player;
}

/**
 * @param $player
 * @return string
 */
function getPlayerImagePlaying($player)
{
    $imageContent = '';

    try {
        $imageContent = (new PlayerImagePlaying($player, new \FileTransfer\SshTransfer()))->download();
    } catch (\Exception $e) {
        addUserMessage(MESSAGE_LEVEL_ERROR, $e->getMessage());
    }

    return $imageContent;
}

/**
 * @param $player
 * @return string string containing file (from file_get_contents)
 */
function getPlayerImageWinning($player)
{
    if ($player instanceof NullPlayer) {
        return '';
    }

    $imageContent = '';
    try {
        $imageContent = (new PlayerImageWinning($player, new \FileTransfer\SshTransfer()))->download();
    } catch (\Exception $e) {
        addUserMessage(MESSAGE_LEVEL_ERROR, $e->getMessage());
    }

    return $imageContent;
}

function uploadImages()
{
    //822 x 654
    if (!empty($_FILES['imgPlaying']['tmp_name'])) {
        (new PlayerImagePlaying(getPlayer(), new \FileTransfer\SshTransfer()))->upload($_FILES['imgPlaying']['tmp_name']);
    }
    if (!empty($_FILES['imgWinning']['tmp_name'])) {
        (new PlayerImageWinning(getPlayer(), new \FileTransfer\SshTransfer()))->upload($_FILES['imgWinning']['tmp_name']);
    }
}

function updatePlayer()
{
    if (!isset($_REQUEST['SavePlayerSettings'])) {
        return;
    }

    $newPlayer = new Player(null, $_REQUEST['rfid'], $_REQUEST['name'], $_REQUEST['name'] . '.png', $_REQUEST['gender']);

    try {
        (new PlayerMysql())->save($newPlayer);
        addUserMessage(MESSAGE_LEVEL_SUCCESS, 'successfully saved player settings');
    } catch (\Exception $e) {
        addUserMessage(MESSAGE_LEVEL_ERROR, "could not save player settings: " . $e->getMessage());
    }
}

/**
 * @param $level
 * @param $message
 */
function addUserMessage($level, $message)
{
    if (!in_array($level, [MESSAGE_LEVEL_ERROR, MESSAGE_LEVEL_INFO, MESSAGE_LEVEL_SUCCESS, MESSAGE_LEVEL_WARNING])) {
        $GLOBALS['message'][MESSAGE_LEVEL_ERROR] = "invalid message level=$level";
        return;
    }
    $GLOBALS['message'][$level] = $message;
}

?>