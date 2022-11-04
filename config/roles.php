<?php

/* 
   Roles definitions (binaries)
   To create a new role, just add a new superglobal with an additional bit
   To insert a role with multiple privileges, just take the bit of the role
   needed and add it to the role of the created user. And insert the binary in 
   the role values in SQL.
*/

$__SUPERADMIN    = 0b00001110;
$__ADMIN         = 0b00000100;
$__AUDITEUR      = 0b00000010;
$__FORMATEUR     = 0b00000001;

// Check if the given parameter is equal to the superadmin role (return true if true)

function isSuperAdmin($role) {

    global $__SUPERADMIN;
    return $role & $__SUPERADMIN;

}

// Check if the given parameter is equal to the admin role (return true if true)

function isAdmin($role) {

    global $__ADMIN;
    return $role & $__ADMIN;

}

// Check if the given parameter is equal to the auditeur role (return true if true)

function isAuditeur($role) {

    global $__AUDITEUR;
    return $role & $__AUDITEUR;

}

// Check if the given parameter is equal to the superadmin role (return true if true)

function isFormateur($role) {

    global $__FORMATEUR;
    return $role & $__FORMATEUR;
    
}

// Those methods are used to show/hide things in page, granting access to a certain content of the portal, ...

// The methods below convert the role int of a user to a string displaying the role

function roleToStr($role) {

    if($role == 1) {

        return "Utilisateur";

    } elseif($role == 2) {

        return "Auditeur";

    } elseif($role == 4) {

        return "Administrateur";

    } elseif($role == 14) {

        return "Superadministrateur";

    }

}

?>