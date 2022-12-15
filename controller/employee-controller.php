<?php require_once('../db/db.php') ?>
<?php require_once('../misc/phone-parser.php') ?>
<?php
    $db = new DB();

    function addEmployee($request) {
        global $db;
        $result = [];

        try {
            $query = "INSERT INTO all_employees(eid, email, name, designation, department, created_at, updated_at) 
                VALUES('{$request["eid"]}', '{$request["email"]}', '{$request["name"]}', '{$request["designation"]}', '{$request["department"]}', NOW(), NOW())";
            $db->query($query);
            $employeeId = $db->lastInsertId();

            $phoneInfo = PhoneParser::analysisPhoneNumber($request['phone']);
            if (!$db->numRows($db->query("SELECT id FROM all_phone_book WHERE prefix = '{$phoneInfo['prefix']}' AND number = '{$phoneInfo['number']}'"))) {
                $query = "INSERT INTO all_phone_book(prefix, number, name) 
                    VALUES('{$phoneInfo['prefix']}', '{$phoneInfo['number']}', '{$phoneInfo['country']}')";
                $db->query($query);
                $phoneBookId = $db->lastInsertId();
                
                $query = "INSERT INTO all_phone_book_links(phone_book_id, table_id, table_name) 
                    VALUES('{$phoneBookId}', '{$employeeId}', 'all_employees')";
                $db->query($query);
            }

            $result = $db->query("
                SELECT emp.*, pb.prefix, pb.number, pb.name as country FROM all_employees emp 
                    LEFT JOIN all_phone_book_links pbl ON emp.id = pbl.table_id AND pbl.table_name = 'all_employees'
                    LEFT JOIN all_phone_book pb ON pbl.phone_book_id = pb.id
                WHERE emp.id = '{$employeeId}'
                ORDER BY emp.id DESC
            ");
            $result = $db->fetchAssoc($result);
        } catch (Exception $exception) {
            //
        }

        return json_encode($result);
    }

    function editEmployee($request) {
        global $db;
        $result = [];

        try {
            $query = "SELECT * FROM all_employees WHERE id = '{$request['id']}'";
            $employee = $db->fetchAssoc($db->query($query));
            // return $request['eid'];

            $query = "UPDATE all_employees 
                SET eid = '{$request["eid"]}',
                email = '{$request["email"]}',
                name = '{$request["name"]}',
                designation = '{$request["designation"]}',
                department = '{$request["department"]}',
                updated_at = NOW() 
                WHERE id = '{$employee['id']}'";
            $db->query($query);
            
            $phoneInfo = PhoneParser::analysisPhoneNumber($request['phone']);
            if (!$db->numRows($db->query("SELECT id FROM all_phone_book WHERE prefix = '{$phoneInfo['prefix']}' AND number = '{$phoneInfo['number']}'"))) {
                $phoneBookLinks = $db->fetchAll($db->query("SELECT * FROM all_phone_book_links WHERE table_id = '{$employee['id']}' AND table_name = 'all_employees'"));
                foreach ($phoneBookLinks as $link) {
                    $db->query("DELETE FROM all_phone_book WHERE id = '{$link['phone_book_id']}'");
                    $db->query("DELETE FROM all_phone_book_links WHERE link_id = '{$link['link_id']}'");
                }                

                $query = "INSERT INTO all_phone_book(prefix, number, name) 
                    VALUES('{$phoneInfo['prefix']}', '{$phoneInfo['number']}', '{$phoneInfo['country']}')";
                $db->query($query);
                $phoneBookId = $db->lastInsertId();
                
                $query = "INSERT INTO all_phone_book_links(phone_book_id, table_id, table_name) 
                    VALUES('{$phoneBookId}', '{$employee['id']}', 'all_employees')";
                $db->query($query);
            }

            $result = $db->query("
                SELECT emp.*, pb.prefix, pb.number, pb.name as country FROM all_employees emp 
                    LEFT JOIN all_phone_book_links pbl ON emp.id = pbl.table_id AND pbl.table_name = 'all_employees'
                    LEFT JOIN all_phone_book pb ON pbl.phone_book_id = pb.id
                WHERE emp.id = '{$employee['id']}'
                ORDER BY emp.id DESC
            ");
            $result = $db->fetchAssoc($result);
        } catch (Exception $exception) {
            // echo $exception->getMessage();
        }

        return json_encode($result);
    }

    function deleteEmployee($request) {
        global $db;
        $result = false;

        try {
            $query = "SELECT * FROM all_employees WHERE id = '{$request['id']}'";
            $employee = $db->fetchAssoc($db->query($query));
            $phoneBookLinks = $db->fetchAll($db->query("SELECT * FROM all_phone_book_links WHERE table_id = '{$employee['id']}' AND table_name = 'all_employees'"));
            foreach ($phoneBookLinks as $link) {
                $db->query("DELETE FROM all_phone_book WHERE id = '{$link['phone_book_id']}'");
                $db->query("DELETE FROM all_phone_book_links WHERE link_id = '{$link['link_id']}'");
            }
            $db->query("DELETE FROM all_employees WHERE id = '{$employee['id']}'");
            $result = true;
        } catch (Exception $exception) {
            // echo $exception->getMessage();
        }

        return json_encode($result);
    }

    if (isset($_POST['form_type'])) {
        if ($_POST['form_type'] === 'add') {
            echo addEmployee($_POST);
        } elseif ($_POST['form_type'] === 'edit') {
            echo editEmployee($_POST);
        } elseif ($_POST['form_type'] === 'delete') {
            echo deleteEmployee($_POST);
        }
    }

    $db->close();
?>