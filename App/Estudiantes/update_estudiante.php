<?php
include_once __DIR__ . '/../Estudiantes/header.php';


$email = $_SESSION['email'];

// delete feedback
if (isset($_SESSION['docente']) && @$_GET['fdid'] && $_SESSION['docente'] == 'docente') {
    $id = @$_GET['fdid'];
    $result = mysqli_query($conexion, "DELETE FROM feedback WHERE id='$id'") or die('Error');
    header("location:evaluaciones_estudiante?q=3");
}

// delete estudiantes
if (isset($_SESSION['docente']) && @$_GET['demail'] && $_SESSION['docente'] == 'docente') {
    $demail = @$_GET['demail'];
    $r1 = mysqli_query($conexion, "DELETE FROM rank WHERE email='$demail'") or die('Error');
    $r2 = mysqli_query($conexion, "DELETE FROM history WHERE email='$demail'") or die('Error');
    $result = mysqli_query($conexion, "DELETE FROM estudiantes WHERE email='$demail'") or die('Error');
    header("location:evaluaciones_estudiante?q=1");
}

// remove quiz
if (isset($_SESSION['docente']) && @$_GET['q'] == 'rmquiz' && $_SESSION['docente'] == 'docente') {
    $eid = @$_GET['eid'];
    $result = mysqli_query($conexion, "SELECT * FROM questions WHERE eid='$eid'") or die('Error');
    while ($row = mysqli_fetch_array($result)) {
        $qid = $row['qid'];
        $r1 = mysqli_query($conexion, "DELETE FROM options WHERE qid='$qid'") or die('Error');
        $r2 = mysqli_query($conexion, "DELETE FROM answer WHERE qid='$qid'") or die('Error');
    }
    $r3 = mysqli_query($conexion, "DELETE FROM questions WHERE eid='$eid'") or die('Error');
    $r4 = mysqli_query($conexion, "DELETE FROM quiz WHERE eid='$eid'") or die('Error');
    $r4 = mysqli_query($conexion, "DELETE FROM history WHERE eid='$eid'") or die('Error');

    header("location:evaluaciones_estudiante?q=5");
}

// add quiz
if (isset($_SESSION['docente']) && @$_GET['q'] == 'addquiz' && $_SESSION['docente'] == 'docente') {
    $name = $_POST['name'];
    $name = ucwords(strtolower($name));
    $total = $_POST['total'];
    $sahi = $_POST['right'];
    $wrong = $_POST['wrong'];
    $time = $_POST['time'];
    $tag = $_POST['tag'];
    $desc = $_POST['desc'];
    $id = uniqid();
    $q3 = mysqli_query($conexion, "INSERT INTO quiz (id, name, sahi, wrong, total, time, desc, tag, date) VALUES ('$id','$name','$sahi','$wrong','$total','$time','$desc','$tag', NOW())");

    header("location:evaluaciones_estudiante?q=4&step=2&eid=$id&n=$total");
}

// add question
if (isset($_SESSION['docente']) && @$_GET['q'] == 'addqns' && $_SESSION['docente'] == 'docente') {
    $n = @$_GET['n'];
    $eid = @$_GET['eid'];
    $ch = @$_GET['ch'];

    for ($i = 1; $i <= $n; $i++) {
        $qid = uniqid();
        $qns = $_POST['qns' . $i];
        $q3 = mysqli_query($conexion, "INSERT INTO questions (eid, qid, qns, ch, sn) VALUES ('$eid','$qid','$qns','$ch','$i')");
        $oaid = uniqid();
        $obid = uniqid();
        $ocid = uniqid();
        $odid = uniqid();
        $a = $_POST[$i . '1'];
        $b = $_POST[$i . '2'];
        $c = $_POST[$i . '3'];
        $d = $_POST[$i . '4'];
        $qa = mysqli_query($conexion, "INSERT INTO options (qid, option, oid) VALUES ('$qid','$a','$oaid')") or die('Error61');
        $qb = mysqli_query($conexion, "INSERT INTO options (qid, option, oid) VALUES ('$qid','$b','$obid')") or die('Error62');
        $qc = mysqli_query($conexion, "INSERT INTO options (qid, option, oid) VALUES ('$qid','$c','$ocid')") or die('Error63');
        $qd = mysqli_query($conexion, "INSERT INTO options (qid, option, oid) VALUES ('$qid','$d','$odid')") or die('Error64');
        $e = $_POST['ans' . $i];
        switch ($e) {
            case 'a':
                $ansid = $oaid;
                break;
            case 'b':
                $ansid = $obid;
                break;
            case 'c':
                $ansid = $ocid;
                break;
            case 'd':
                $ansid = $odid;
                break;
            default:
                $ansid = $oaid;
        }

        $qans = mysqli_query($conexion, "INSERT INTO answer (qid, ansid) VALUES ('$qid','$ansid')");
    }
    header("location:evaluaciones_estudiante?q=0");
}

// quiz start
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
    $eid = @$_GET['eid'];
    $sn = @$_GET['n'];
    $total = @$_GET['t'];
    $ans = $_POST['ans'];
    $qid = @$_GET['qid'];
    $q = mysqli_query($conexion, "SELECT * FROM answer WHERE qid='$qid'");
    while ($row = mysqli_fetch_array($q)) {
        $ansid = $row['ansid'];
    }
    if ($ans == $ansid) {
        $q = mysqli_query($conexion, "SELECT * FROM quiz WHERE eid='$eid'");
        while ($row = mysqli_fetch_array($q)) {
            $sahi = $row['sahi'];
        }
        if ($sn == 1) {
            $q = mysqli_query($conexion, "INSERT INTO history (email, eid, score, sahi, wrong, level, date) VALUES ('$email','$eid','0','0','0','0',NOW())") or die('Error');
        }
        $q = mysqli_query($conexion, "SELECT * FROM history WHERE eid='$eid' AND email='$email'") or die('Error115');
        while ($row = mysqli_fetch_array($q)) {
            $s = $row['score'];
            $r = $row['sahi'];
        }
        $r++;
        $s = $s + $sahi;
        $q = mysqli_query($conexion, "UPDATE `history` SET `score`=$s,`level`=$sn,`sahi`=$r, date=NOW()  WHERE  email = '$email' AND eid = '$eid'") or die('Error124');
    } else {
        $q = mysqli_query($conexion, "SELECT * FROM quiz WHERE eid='$eid'") or die('Error129');
        while ($row = mysqli_fetch_array($q)) {
            $wrong = $row['wrong'];
        }
        if ($sn == 1) {
            $q = mysqli_query($conexion, "INSERT INTO history (email, eid, score, sahi, wrong, level, date) VALUES ('$email','$eid','0','0','0','0',NOW())") or die('Error137');
        }
        $q = mysqli_query($conexion, "SELECT * FROM history WHERE eid='$eid' AND email='$email'") or die('Error139');
        while ($row = mysqli_fetch_array($q)) {
            $s = $row['score'];
            $w = $row['wrong'];
        }
        $w++;
        $s = max(0, $s - $wrong); // Evitar puntaje negativo
        $q = mysqli_query($conexion, "UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  email = '$email' AND eid = '$eid'") or die('Error147');
    }
    if ($sn != $total) {
        $sn++;
        header("location:account_estudiante?q=quiz&step=2&eid=$eid&n=$sn&t=$total") or die('Error152');
    } else if ($_SESSION['docente'] != 'docente') {
        $q = mysqli_query($conexion, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error156');
        while ($row = mysqli_fetch_array($q)) {
            $s = $row['score'];
        }
        $q = mysqli_query($conexion, "SELECT * FROM rank WHERE email='$email'") or die('Error161');
        $rowcount = mysqli_num_rows($q);
        if ($rowcount == 0) {
            $q2 = mysqli_query($conexion, "INSERT INTO rank (email, score, time) VALUES ('$email','$s',NOW())") or die('Error165');
        } else {
            while ($row = mysqli_fetch_array($q)) {
                $sun = $row['score'];
            }
            $sun = $s + $sun;
            $q = mysqli_query($conexion, "UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'") or die('Error174');
        }
        header("location:account_estudiante?q=result&eid=$eid");
    } else {
        header("location:account_estudiante?q=result&eid=$eid");
    }
}

// restart quiz
if (@$_GET['q'] == 'quizre' && @$_GET['step'] == 25) {
    $eid = @$_GET['eid'];
    $n = @$_GET['n'];
    $t = @$_GET['t'];
    $q = mysqli_query($conexion, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error156');
    while ($row = mysqli_fetch_array($q)) {
        $s = $row['score'];
    }
    $q = mysqli_query($conexion, "DELETE FROM `history` WHERE eid='$eid' AND email='$email'") or die('Error184');
    $q = mysqli_query($conexion, "SELECT * FROM rank WHERE email='$email'") or die('Error161');
    while ($row = mysqli_fetch_array($q)) {
        $sun = $row['score'];
    }
    $sun = max(0, $sun - $s); // Evitar puntaje negativo
    $q = mysqli_query($conexion, "UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'") or die('Error174');
    header("location:account_estudiante?q=quiz&step=2&eid=$eid&n=1&t=$t");
}
?>
