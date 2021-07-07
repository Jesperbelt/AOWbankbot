<?php

include __DIR__ . '/vendor/autoload.php';

?>

<?php
include 'Connection.php';

function person($query, $id)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $query);
    mysqli_stmt_bind_param($statement, 's', $id);
    mysqli_stmt_execute($statement);
    $data = mysqli_stmt_get_result($statement);
    $data = mysqli_fetch_all($data, MYSQLI_ASSOC);
    return $data;
}

function addperson($query, $id, $user)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $query);
    mysqli_stmt_bind_param($statement, 'ss', $id, $user);
    mysqli_stmt_execute($statement);
}

function total($update, $id)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $update);
    mysqli_stmt_bind_param($statement, 'sssssss', $id, $id, $id, $id, $id, $id, $id);
    mysqli_stmt_execute($statement);
}

function whatweek($queryweek)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $queryweek);
    mysqli_stmt_execute($statement);
    $week = mysqli_stmt_get_result($statement);
    $week = mysqli_fetch_all($week, MYSQLI_ASSOC);
    return $week;
}

function whatweek2($queryweek2, $week)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $queryweek2);
    mysqli_stmt_bind_param($statement, 's', $week);
    mysqli_stmt_execute($statement);
    $valid = mysqli_stmt_get_result($statement);
    $valid = mysqli_fetch_all($valid, MYSQLI_ASSOC);
    return $valid;
}

function participants($wtaparticipantsquery, $week)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $wtaparticipantsquery);
    mysqli_stmt_bind_param($statement, 's', $week);
    mysqli_stmt_execute($statement);
    $names = mysqli_stmt_get_result($statement);
    $names = mysqli_fetch_all($names, MYSQLI_ASSOC);
    return $names;
}

function totals($totalsquery, $week, $name)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $totalsquery);
    mysqli_stmt_bind_param($statement, 'ss', $week, $name);
    mysqli_stmt_execute($statement);
    $totals = mysqli_stmt_get_result($statement);
    $totals = mysqli_fetch_all($totals, MYSQLI_ASSOC);
    return $totals;
}

?>
<?php

use Discord\DiscordCommandClient;

$discord = new DiscordCommandClient([
    'token' => 'Nzg5MjUxMDU3NDQzNjY4MDQ5.X9vVUw.Y_-s_-Q8qya-Lf6X_Bq-fnRuwCw',
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;

    // Listen for events here
    $discord->on('message', function ($message) {

        echo "Recieved a message from {$message->author->username}: {$message->content}", PHP_EOL;

        if (preg_match('/!/', $message->content)) {
            $command = $message->content;
            $command = explode(" ", $command, 4);
            $first = $command[0];
            if (preg_match('/!info/', $first)) {
                $id = $message->author->id;
                $query = "SELECT name FROM person_info WHERE id=?";
                $data = person($query, $id);
                if (isset($data[0]['name'])) {
                    $message->reply($data[0]['name']);
                } else {
                    $message->reply("You don't exist yet");
                }
            }
            if (preg_match('/!help/', $first) or preg_match('/!Help/', $first)) {
                if (isset($command[1])) {
                    $second = $command[1];
                    if (preg_match('/!add/', $second)) {
                        $content3 = "\nCommand: !add\nDescription: Will add your discord name and DiscorID to the dod database\nUsage: !add";
                    }
                    if (preg_match('/!total/', $second) or preg_match('/!Total/', $second)) {
                        $content3 = "\nCommand: !total\nDescription: Personal Totals in the bank\nUsage: !total or !total (@ of a player)";
                    }
                    if (preg_match('/!WTA/', $second) or preg_match('/!wta/', $second)) {
                        $content3 = "\nCommand: !wta\nDescription: Winner Take all Lottery\nUsage: !wta or !wta (Number of the week)";
                    }
                    if (preg_match('-!50/50-', $second) or preg_match('-!wtf-', $second) or preg_match('-!WTF-', $second)) {
                        $content3 = "\nCommand: !50/50 or !wtf\nDescription: 50/50 raffle. Half to the winner half to the guild\nUsage: !50/50 / !wtf or !50/50 / !wtf (Number of the week)";
                    }
                    if (preg_match('/!PB/', $second) or preg_match('/!Pb/', $second) or preg_match('/!pb/', $second)) {
                        $content3 = "\nCommand: !pb\nDescription: Power ball, played just like the power ball\nUsage: !pb or !pb (Number of the week)";
                    }
                    if (preg_match('/!guild/', $second) or preg_match('/!Guild/', $second)) {
                        $content3 = "\nCommand: !guild\nDescription: Displays our current guild funds\nUsage: !guild)";
                    }
                    if (preg_match('/!tracker/', $second) or preg_match('/!Tracker/', $second) or preg_match('/!TRACKER/', $second)) {
                        $content3 = "\nCommand: !tracker\nDescription: What you have personally donated to the guild(this is also half of what you put into the 50/50 raffle)\nUsage: !tracker or !tracker (@ of a player)";
                    }
                    if (preg_match('/!gear/', $second) or preg_match('/!Gear/', $second)) {
                        $content3 = "\nCommand: !gear\nDescription: Will allow you to calculate basestat of gear and stats of gear when upgraded\nUsage: Basestatcalc: !gear (Stat number) (Gear level) or Upgradestatcalc:  !gear (Stat number) (Gear level) (Upgrade Gear Level)";
                    }
                } else {
                    $content1 = "You can use the following commands:\n* !add\n* !total\n* !wta\n* !50/50 or !wtf\n* !pb\n* !guild\n* !tracker\n* !gear\n";
                    $content2 = "If you want more detailed help use the command like this !help (command)";
                }
                if (isset($content3)) {
                    $content = $content3;
                } else {
                    $content = $content1 . $content2;
                }
                $message->reply($content);
            }
            if (preg_match('/!add/', $first)) {
                $id = $message->author->id;
                $query = "SELECT id FROM person_info WHERE id=?";
                $data = person($query, $id);
                if (!($data)) {
                    $user = $message->author->username;
                    $query = "INSERT INTO person_info(id,name) values (?,?)";
                    addperson($query, $id, $user);
                } else {
                    $message->reply("You already exist");
                }
            }
            if (preg_match('/!total/', $first) or preg_match('/!Total/', $first)) {
                $id = $message->author->id;
                if (isset($command[1])) {
                    $id = preg_replace('/<@!/', "", "$command[1]");
                    echo $id;
                }
                $update = "UPDATE `Total_bank` SET Tfood=(SELECT SUM(food) FROM Data_bank WHERE id=? AND type='Personal'),Tparts=(SELECT SUM(parts) FROM Data_bank WHERE id=? AND type='Personal'),Telectric=(SELECT SUM(electric) FROM Data_bank WHERE id=? AND type='Personal'),Tgas=(SELECT SUM(gas) FROM Data_bank WHERE id=? AND type='Personal'),Tcash=(SELECT SUM(cash) FROM Data_bank WHERE id=? AND type='Personal'),Tshadow=(SELECT SUM(shadow) FROM Data_bank WHERE id=? AND type='Personal') WHERE id=?";
                total($update, $id);
                $query = "SELECT name,Tfood,Tparts,Telectric,Tgas,Tcash,Tshadow FROM Total_bank WHERE id=?";
                $data = person($query, $id);
                $message->reply("Your personal totals are:" . "\n<@&790302677761392660>: " . round($data[0]['Tfood']) . "M" . "\n<@&790302779314143303>: " . round($data[0]['Tparts']) . "M" . "\n<@&790302852861919312>: " . round($data[0]['Telectric']) . "M" . "\n<@&790312643474358314>: " . round($data[0]['Tgas']) . "M" . "\n<@&790312798500028437>: " . round($data[0]['Tcash']) . "M" . "\n<@&790312854184001547>: " . round($data[0]['Tshadow']));
            }
            if (preg_match('/!WTA/', $first) or preg_match('/!wta/', $first)) {
                echo 1;
                if (isset($command[1])) {
                    $second = $command[1];
                }
                if (preg_match('/[0-9]/', $second)) {
                    echo 2;
                    $week = 'Week' . $second;
                    $queryweek2 = "SELECT lotteryweek FROM Data_bank WHERE lotteryweek=? AND type='WTA' LIMIT 1";
                    $valid = whatweek2($queryweek2, $week);
                    if (isset($valid)) {
                        $valid = $valid[0]['lotteryweek'];
                        $times = trim($valid, "Week");
                        $wtaparticipantsquery = "SELECT DISTINCT name FROM `Data_bank` WHERE lotteryweek=? AND type='WTA' ";
                        $names = participants($wtaparticipantsquery, $week);
                        $values = "<@&790302677761392660> <@&790302779314143303> <@&790302852861919312> <@&790312643474358314> <@&790312798500028437>\n";
                        foreach ($names as $name) {
                            $totalsquery = "SELECT SUM(food) as f,SUM(parts) as p,SUM(electric) as e,SUM(gas) as g,SUM(cash) as c FROM `Data_bank` WHERE lotteryweek=? AND type='WTA' AND name=?";
                            $name = $name['name'];
                            print($week);
                            $totals = totals($totalsquery, $week, $name);
                            if (strlen(round($totals[0]['f'])) == 1) {
                                $fspace = " ";
                            } else {
                                $fspace = "";
                            }
                            if (strlen(round($totals[0]['p'])) == 1) {
                                $pspace = " ";
                            } else {
                                $pspace = "";
                            }
                            if (strlen(round($totals[0]['e'])) == 1) {
                                $espace = " ";
                            } else {
                                $espace = "";
                            }
                            if (strlen(round($totals[0]['g'])) == 1) {
                                $gspace = " ";
                            } else {
                                $gspace = "";
                            }
                            if (strlen(round($totals[0]['c'])) == 1) {
                                $cspace = " ";
                            } else {
                                $cspace = "";
                            }
                            $values = $values . "` |" . $food = round($totals[0]['f']) . $fspace . "|   |" . round($totals[0]['p']) . $pspace . "|    |" . round($totals[0]['e']) . $espace . "|   |" . round($totals[0]['g']) . $gspace . "|  |" . round($totals[0]['c']) . $cspace . "|  -" . $name . "`" . "\n";
                        }
                        $message->reply("Current WTA of Week: " . $times . "\n" . $values);
                    }
                } else {
                    echo 3;
                    $queryweek = "SELECT lotteryweek FROM Data_bank WHERE lotteryweek LIKE 'Week%' AND type='WTA' ORDER BY lineid DESC LIMIT 1";
                    $week = whatweek($queryweek);
                    $week = $week[0]['lotteryweek'];
                    $times = trim($week, "Week");
                    $wtaparticipantsquery = "SELECT DISTINCT name FROM `Data_bank` WHERE lotteryweek=? AND type='WTA' ";
                    $names = participants($wtaparticipantsquery, $week);
                    $values = "<@&790302677761392660> <@&790302779314143303> <@&790302852861919312> <@&790312643474358314> <@&790312798500028437>\n";
                    foreach ($names as $name) {
                        $totalsquery = "SELECT SUM(food) as f,SUM(parts) as p,SUM(electric) as e,SUM(gas) as g,SUM(cash) as c FROM `Data_bank` WHERE lotteryweek=? AND type='WTA' AND name=?";
                        $name = $name['name'];
                        print($week);
                        $totals = totals($totalsquery, $week, $name);
                        if (strlen(round($totals[0]['f'])) == 1) {
                            $fspace = " ";
                        } else {
                            $fspace = "";
                        }
                        if (strlen(round($totals[0]['p'])) == 1) {
                            $pspace = " ";
                        } else {
                            $pspace = "";
                        }
                        if (strlen(round($totals[0]['e'])) == 1) {
                            $espace = " ";
                        } else {
                            $espace = "";
                        }
                        if (strlen(round($totals[0]['g'])) == 1) {
                            $gspace = " ";
                        } else {
                            $gspace = "";
                        }
                        if (strlen(round($totals[0]['c'])) == 1) {
                            $cspace = " ";
                        } else {
                            $cspace = "";
                        }
                        $values = $values . "` |" . $food = round($totals[0]['f']) . $fspace . "|   |" . round($totals[0]['p']) . $pspace . "|    |" . round($totals[0]['e']) . $espace . "|   |" . round($totals[0]['g']) . $gspace . "|  |" . round($totals[0]['c']) . $cspace . "|  -" . $name . "`" . "\n";
                    }
                    $message->reply("Current WTA of Week: " . $times . "\n" . $values);
                }
            }
            if (preg_match('-!50/50-', $first) or preg_match('-!wtf-', $first) or preg_match('-!WTF-', $first)) {
                echo 1;
                if (isset($command[1])) {
                    $second = $command[1];
                }
                if (preg_match('/[0-9]/', $second)) {
                    echo 2;
                    $week = 'Week' . $second;
                    $queryweek2 = "SELECT lotteryweek FROM Data_bank WHERE lotteryweek=? AND type='Lottery' LIMIT 1";
                    $valid = whatweek2($queryweek2, $week);
                    if (isset($valid)) {
                        $valid = $valid[0]['lotteryweek'];
                        $times = trim($valid, "Week");
                        $wtaparticipantsquery = "SELECT DISTINCT name FROM `Data_bank` WHERE lotteryweek=? AND type='Lottery' ";
                        $names = participants($wtaparticipantsquery, $week);
                        $values = "<@&790302677761392660> <@&790302779314143303> <@&790302852861919312> <@&790312643474358314> <@&790312798500028437>\n";
                        foreach ($names as $name) {
                            $totalsquery = "SELECT SUM(food) as f,SUM(parts) as p,SUM(electric) as e,SUM(gas) as g,SUM(cash) as c FROM `Data_bank` WHERE lotteryweek=? AND type='Lottery' AND name=?";
                            $name = $name['name'];
                            print($week);
                            $totals = totals($totalsquery, $week, $name);
                            if (strlen(round($totals[0]['f'])) == 1) {
                                $fspace = " ";
                            } else {
                                $fspace = "";
                            }
                            if (strlen(round($totals[0]['p'])) == 1) {
                                $pspace = " ";
                            } else {
                                $pspace = "";
                            }
                            if (strlen(round($totals[0]['e'])) == 1) {
                                $espace = " ";
                            } else {
                                $espace = "";
                            }
                            if (strlen(round($totals[0]['g'])) == 1) {
                                $gspace = " ";
                            } else {
                                $gspace = "";
                            }
                            if (strlen(round($totals[0]['c'])) == 1) {
                                $cspace = " ";
                            } else {
                                $cspace = "";
                            }
                            $values = $values . "` |" . $food = round($totals[0]['f']) . $fspace . "|   |" . round($totals[0]['p']) . $pspace . "|    |" . round($totals[0]['e']) . $espace . "|   |" . round($totals[0]['g']) . $gspace . "|  |" . round($totals[0]['c']) . $cspace . "|  -" . $name . "`" . "\n";
                        }
                        $message->reply("Current 50/50 of Week: " . $times . "\n" . $values);
                    }
                } else {
                    echo 3;
                    $queryweek = "SELECT lotteryweek FROM Data_bank WHERE lotteryweek LIKE 'Week%' AND type='Lottery' ORDER BY lineid DESC LIMIT 1";
                    $week = whatweek($queryweek);
                    $week = $week[0]['lotteryweek'];
                    $times = trim($week, "Week");
                    $wtaparticipantsquery = "SELECT DISTINCT name FROM `Data_bank` WHERE lotteryweek=? AND type='Lottery' ";
                    $names = participants($wtaparticipantsquery, $week);
                    $values = "<@&790302677761392660> <@&790302779314143303> <@&790302852861919312> <@&790312643474358314> <@&790312798500028437>\n";
                    foreach ($names as $name) {
                        $totalsquery = "SELECT SUM(food) as f,SUM(parts) as p,SUM(electric) as e,SUM(gas) as g,SUM(cash) as c FROM `Data_bank` WHERE lotteryweek=? AND type='Lottery' AND name=?";
                        $name = $name['name'];
                        print($week);
                        $totals = totals($totalsquery, $week, $name);
                        if (strlen(round($totals[0]['f'])) == 1) {
                            $fspace = " ";
                        } else {
                            $fspace = "";
                        }
                        if (strlen(round($totals[0]['p'])) == 1) {
                            $pspace = " ";
                        } else {
                            $pspace = "";
                        }
                        if (strlen(round($totals[0]['e'])) == 1) {
                            $espace = " ";
                        } else {
                            $espace = "";
                        }
                        if (strlen(round($totals[0]['g'])) == 1) {
                            $gspace = " ";
                        } else {
                            $gspace = "";
                        }
                        if (strlen(round($totals[0]['c'])) == 1) {
                            $cspace = " ";
                        } else {
                            $cspace = "";
                        }
                        $values = $values . "` |" . $food = round($totals[0]['f']) . $fspace . "|   |" . round($totals[0]['p']) . $pspace . "|    |" . round($totals[0]['e']) . $espace . "|   |" . round($totals[0]['g']) . $gspace . "|  |" . round($totals[0]['c']) . $cspace . "|  -" . $name . "`" . "\n";
                    }
                    $message->reply("Current 50/50 of Week: " . $times . "\n" . $values);
                }
            }
            if (preg_match('/!PB/', $first) or preg_match('/!Pb/', $first) or preg_match('/!pb/', $first)) {
                echo 1;
                if (isset($command[1])) {
                    $second = $command[1];
                }
                if (preg_match('/[0-9]/', $second)) {
                    echo 2;
                    $week = 'Week' . $second;
                    $queryweek2 = "SELECT lotteryweek FROM Data_bank WHERE lotteryweek=? AND type='Power Ball' LIMIT 1";
                    $valid = whatweek2($queryweek2, $week);
                    if (isset($valid)) {
                        $valid = $valid[0]['lotteryweek'];
                        $times = trim($valid, "Week");
                        $wtaparticipantsquery = "SELECT DISTINCT name FROM `Data_bank` WHERE lotteryweek=? AND type='Power Ball' ";
                        $names = participants($wtaparticipantsquery, $week);
                        $values = "<@&790302677761392660> <@&790302779314143303> <@&790302852861919312> <@&790312643474358314> <@&790312798500028437>\n";
                        foreach ($names as $name) {
                            $totalsquery = "SELECT SUM(food) as f,SUM(parts) as p,SUM(electric) as e,SUM(gas) as g,SUM(cash) as c FROM `Data_bank` WHERE lotteryweek=? AND type='Power Ball' AND name=?";
                            $name = $name['name'];
                            print($week);
                            $totals = totals($totalsquery, $week, $name);
                            if (strlen(round($totals[0]['f'])) == 1) {
                                $fspace = " ";
                            } else {
                                $fspace = "";
                            }
                            if (strlen(round($totals[0]['p'])) == 1) {
                                $pspace = " ";
                            } else {
                                $pspace = "";
                            }
                            if (strlen(round($totals[0]['e'])) == 1) {
                                $espace = " ";
                            } else {
                                $espace = "";
                            }
                            if (strlen(round($totals[0]['g'])) == 1) {
                                $gspace = " ";
                            } else {
                                $gspace = "";
                            }
                            if (strlen(round($totals[0]['c'])) == 1) {
                                $cspace = " ";
                            } else {
                                $cspace = "";
                            }
                            $values = $values . "` |" . $food = round($totals[0]['f']) . $fspace . "|   |" . round($totals[0]['p']) . $pspace . "|    |" . round($totals[0]['e']) . $espace . "|   |" . round($totals[0]['g']) . $gspace . "|  |" . round($totals[0]['c']) . $cspace . "|  -" . $name . "`" . "\n";
                        }
                        $message->reply("Current Power Ball of Week: " . $times . "\n" . $values);
                    }
                } else {
                    echo 3;
                    $queryweek = "SELECT lotteryweek FROM Data_bank WHERE lotteryweek LIKE 'Week%' AND type='Power Ball' ORDER BY lineid DESC LIMIT 1";
                    $week = whatweek($queryweek);
                    $week = $week[0]['lotteryweek'];
                    $times = trim($week, "Week");
                    $wtaparticipantsquery = "SELECT DISTINCT name FROM `Data_bank` WHERE lotteryweek=? AND type='Power Ball' ";
                    $names = participants($wtaparticipantsquery, $week);
                    $values = "<@&790302677761392660> <@&790302779314143303> <@&790302852861919312> <@&790312643474358314> <@&790312798500028437>\n";
                    foreach ($names as $name) {
                        $totalsquery = "SELECT SUM(food) as f,SUM(parts) as p,SUM(electric) as e,SUM(gas) as g,SUM(cash) as c FROM `Data_bank` WHERE lotteryweek=? AND type='Power Ball' AND name=?";
                        $name = $name['name'];
                        print($week);
                        $totals = totals($totalsquery, $week, $name);
                        if (strlen(round($totals[0]['f'])) == 1) {
                            $fspace = " ";
                        } else {
                            $fspace = "";
                        }
                        if (strlen(round($totals[0]['p'])) == 1) {
                            $pspace = " ";
                        } else {
                            $pspace = "";
                        }
                        if (strlen(round($totals[0]['e'])) == 1) {
                            $espace = " ";
                        } else {
                            $espace = "";
                        }
                        if (strlen(round($totals[0]['g'])) == 1) {
                            $gspace = " ";
                        } else {
                            $gspace = "";
                        }
                        if (strlen(round($totals[0]['c'])) == 1) {
                            $cspace = " ";
                        } else {
                            $cspace = "";
                        }
                        $values = $values . "` |" . $food = round($totals[0]['f']) . $fspace . "|   |" . round($totals[0]['p']) . $pspace . "|    |" . round($totals[0]['e']) . $espace . "|   |" . round($totals[0]['g']) . $gspace . "|  |" . round($totals[0]['c']) . $cspace . "|  -" . $name . "`" . "\n";
                    }
                    $message->reply("Current Power Ball of Week: " . $times . "\n" . $values);
                }
            }
            if (preg_match('/!guild/', $first) or preg_match('/!Guild/', $first)) {
                $query = "SELECT sum(food) as Tfood,sum(parts) as Tparts,sum(electric) as Telectric,sum(gas) as Tgas,sum(cash) as Tcash FROM `Data_bank` WHERE type='Guild'";
                $data = whatweek($query);
                $message->reply("Guild totals are:" . "\n<@&790302677761392660>: " . round($data[0]['Tfood']) . "M" . "\n<@&790302779314143303>: " . round($data[0]['Tparts']) . "M" . "\n<@&790302852861919312>: " . round($data[0]['Telectric']) . "M" . "\n<@&790312643474358314>: " . round($data[0]['Tgas']) . "M" . "\n<@&790312798500028437>: " . round($data[0]['Tcash']) . "M" . "\n<@&790312854184001547>: " . round($data[0]['Tshadow']));
            }
            if (preg_match('/!tracker/', $first) or preg_match('/!Tracker/', $first) or preg_match('/!TRACKER/', $first)) {
                $id = $message->author->id;
                if (isset($command[1])) {
                    $id = preg_replace('/<@!/', "", "$command[1]");
                    echo $id;
                }
                $query = "SELECT sum(food) as Tfood,sum(parts) as Tparts,sum(electric) as Telectric,sum(gas) as Tgas,sum(cash) as Tcash from Data_bank WHERE type='guild' and id=?";
                $rssguild = person($query, $id);
                $query = "SELECT sum(food) as Tfood,sum(parts) as Tparts,sum(electric) as Telectric,sum(gas) as Tgas,sum(cash) as Tcash from Data_bank WHERE type='lottery' and id=?";
                $rsslottery = person($query, $id);
                if (isset($rsslottery) and isset($rssguild)) {
                    $food = $rssguild[0]['Tfood'] + ($rsslottery[0]['Tfood'] / 2);
                    $parts = $rssguild[0]['Tparts'] + ($rsslottery[0]['Tparts'] / 2);
                    $electric = $rssguild[0]['Telectric'] + ($rsslottery[0]['Telectric'] / 2);
                    $gas = $rssguild[0]['Tgas'] + ($rsslottery[0]['Tgas'] / 2);
                    $cash = $rssguild[0]['Tcash'] + ($rsslottery[0]['Tcash'] / 2);
                } elseif (isset($rssguild)) {
                    $food = $rssguild[0]['Tfood'];
                    $parts = $rssguild[0]['Tparts'];
                    $electric = $rssguild[0]['Telectric'];
                    $gas = $rssguild[0]['Tgas'];
                    $cash = $rssguild[0]['Tcash'];
                } elseif (isset($rsslottery)) {
                    $food = $rsslottery[0]['Tfood'] / 2;
                    $parts = $rsslottery[0]['Tparts'] / 2;
                    $electric = $rsslottery[0]['Telectric'] / 2;
                    $gas = $rsslottery[0]['Tgas'] / 2;
                    $cash = $rsslottery[0]['Tcash'] / 2;
                } else {
                    $food = 0;
                    $parts = 0;
                    $electric = 0;
                    $gas = 0;
                    $cash = 0;
                }
                if (isset($rsslottery) or isset($rssguild)) {
                    $query = "SELECT startdate FROM `person_info` WHERE id=?";
                    $data = person($query, $id);
                    if (empty($data)) {
                        $query = "SELECT date FROM `Data_bank` WHERE type='guild' and id=? ORDER by date DESC LIMIT 1";
                        $data = person($query, $id);
                        $earlier = new DateTime($data[0]['date']);
                    } else {
                        $earlier = new DateTime($data[0]['startdate']);
                    }
                    $currentdate = date("Y-m-d");
                    $later = new DateTime($currentdate);
                    $diff = $later->diff($earlier)->format("%a");
                    echo $diff;
                }
                $content = "Your Guild totals are:" . "\n<@&790302677761392660>: " . round($food, 1) . "M" . "\n<@&790302779314143303>: " . round($parts, 1) . "M" . "\n<@&790302852861919312>: " . round($electric, 1) . "M" . "\n<@&790312643474358314>: " . round($gas, 1) . "M" . "\n<@&790312798500028437>: " . round($cash, 1) . "M"
                    . "\n";
                if (isset($data)) {
                    $weeks = $diff / 7;
                    if ($food < $weeks or $parts < $weeks or $electric < $weeks or $gas < $weeks or $cash < $weeks) {
                        $content2 = "----------------\nYou owe the guild:\n";
                        if ($food < $weeks) {
                            $rss = $weeks - $food;
                            $content3 = "<@&790302677761392660>: " . round($rss, 1);
                        }
                        if ($parts < $weeks) {
                            $rss = $weeks - $parts;
                            $content3 = $content3 . " <@&790302779314143303>: " . round($rss, 1);
                        }
                        if ($electric < $weeks) {
                            $rss = $weeks - $electric;
                            $content3 = $content3 . " <@&790302852861919312>: " . round($rss, 1);
                        }
                        if ($gas < $weeks) {
                            $rss = $weeks - $gas;
                            $content3 = $content3 . " <@&790312643474358314>: " . round($rss, 1);
                        }
                        if ($cash < $weeks) {
                            $rss = $weeks - $cash;
                            $content3 = $content3 . " <@&790312798500028437>: " . round($rss, 1);
                        }
                    }
                    if (isset($content2)) {
                        $content = $content . $content2 . $content3;
                    } else {
                        $content = $content;
                    }
                }
                $message->reply($content);
            }
            if (preg_match('/!gear/', $first) or preg_match('/!Gear/', $first)) {
                if (isset($command[1])) {
                    $second = $command[1];
                    if (isset($command[2])) {
                        $third = $command[2];
                        if (isset($command[3])) {
                            $fourth = $command[3];
                            if ($second > 0 and $third >= 0) {
                                if ($fourth >= 1) {
                                    if ($third == 0) {
                                        //not calculate basestat
                                        $basestat = $second;
                                        $upgradestat = ((($basestat / 10) * $fourth) + $basestat);
                                        $content1 = "Gear base stat: $basestat\n";
                                        $content2 = "Gear upgraded stat: $upgradestat";
                                    } else {
                                        $basestat = $second / (1 + ($third / 10));
                                        $upgradestat = ((($basestat / 10) * $fourth) + $basestat);
                                        $content1 = "Gear base stat: $basestat\n";
                                        $content2 = "Gear upgraded stat: $upgradestat";
                                    }
                                } else {
                                    $content5 = "The upgrade lvl you gave is not higher than 1";
                                }
                            }
                        } else {
                            if ($second > 0 and $third > 0) {
                                $basestat = $second / (1 + ($third / 10));
                                $content1 = "Gear base stat: $basestat\n";
                                $content2 = "You have calculated the Base stat of the gear.\nIf you want to calculate what the stat would be at higher lvls add more numbders.";
                            } else {
                                $content1 = "You provided a negative number";
                                $content2 = "\nPlease use a positive number";
                            }
                        }
                    } else {
                        $content3 = "Please provide the current gear_lvl";
                    }
                } else {
                    $content4 = "You didn't provide a gear_stat number\nPlease provide a gear_stat number\n";
                }
                if (isset($content5)) {
                    $content = $content5;
                }
                if (isset($content4)) {
                    $content = $content4;
                }
                if (isset($content3)) {
                    $content = $content3;
                }
                if (isset($content1) and isset($content2)) {
                    $content = $content1 . $content2;
                }
                $message->reply($content);
            }
        }

    }); //end small function with content
}); //end main function ready

$discord->run();
?>



