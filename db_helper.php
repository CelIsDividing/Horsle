<?php
// Helper funkcije za JSON bazu

/**
 * Pronađi korisnike po uslovu
 */
function find_users($where = null) {
    $db = load_db();
    $results = array();
    
    foreach ($db['users'] as $user) {
        if ($where === null) {
            $results[] = $user;
        } else {
            $match = true;
            foreach ($where as $field => $value) {
                if (!isset($user[$field]) || $user[$field] != $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) $results[] = $user;
        }
    }
    return $results;
}

/**
 * Pronađi prvog korisnika po uslovu
 */
function find_user($where) {
    $results = find_users($where);
    return count($results) > 0 ? $results[0] : null;
}

/**
 * Pronađi korisnika po ID-u
 */
function get_user_by_id($id) {
    return find_user(['id' => $id]);
}

/**
 * Kreiraj novog korisnika
 */
function create_user($data) {
    $db = load_db();
    $data['id'] = get_next_id('users');
    $data['created_at'] = date('Y-m-d H:i:s');
    $db['users'][] = $data;
    save_db($db);
    return $data;
}

/**
 * Ažurira korisnika
 */
function update_user($id, $data) {
    $db = load_db();
    foreach ($db['users'] as &$user) {
        if ($user['id'] == $id) {
            foreach ($data as $key => $value) {
                $user[$key] = $value;
            }
            break;
        }
    }
    save_db($db);
}

/**
 * Proveri da li korisnik postoji
 */
function user_exists($username = null, $email = null) {
    $db = load_db();
    foreach ($db['users'] as $user) {
        if ($username && $user['username'] == $username) return true;
        if ($email && $user['email'] == $email) return true;
    }
    return false;
}

/**
 * Dodaj like/dislike
 */
function add_like($user_id, $liked_user_id, $action) {
    $db = load_db();
    
    // Proveri da li već postoji
    foreach ($db['likes'] as $like) {
        if ($like['user_id'] == $user_id && $like['liked_user_id'] == $liked_user_id) {
            return false; // Već postoji
        }
    }
    
    $like = array(
        'id' => get_next_id('likes'),
        'user_id' => $user_id,
        'liked_user_id' => $liked_user_id,
        'action' => $action,
        'created_at' => date('Y-m-d H:i:s')
    );
    $db['likes'][] = $like;
    save_db($db);
    return true;
}

/**
 * Pronađi sve profil koji nisu obradili
 */
function get_unreviewed_profiles($user_id) {
    $db = load_db();
    $liked = array();
    
    foreach ($db['likes'] as $like) {
        if ($like['user_id'] == $user_id) {
            $liked[] = $like['liked_user_id'];
        }
    }
    
    $unreviewed = array();
    foreach ($db['users'] as $user) {
        if ($user['id'] != $user_id && !in_array($user['id'], $liked)) {
            $unreviewed[] = $user;
        }
    }
    
    return count($unreviewed) > 0 ? $unreviewed[0] : null;
}

/**
 * Broji like-ove za korisnika
 */
function count_likes($user_id) {
    $db = load_db();
    $count = 0;
    foreach ($db['likes'] as $like) {
        if ($like['user_id'] == $user_id && $like['action'] == 'like') {
            $count++;
        }
    }
    return $count;
}

/**
 * Broji preglede profila
 */
function count_views($user_id) {
    $db = load_db();
    $count = 0;
    foreach ($db['profiles_viewed'] as $view) {
        if ($view['user_id'] == $user_id) {
            $count++;
        }
    }
    return $count;
}

/**
 * Dodaj view
 */
function add_view($user_id, $viewed_user_id) {
    $db = load_db();
    $view = array(
        'id' => get_next_id('profiles_viewed'),
        'user_id' => $user_id,
        'viewed_user_id' => $viewed_user_id,
        'viewed_at' => date('Y-m-d H:i:s')
    );
    $db['profiles_viewed'][] = $view;
    save_db($db);
}
?>
