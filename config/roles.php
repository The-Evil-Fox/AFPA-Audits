<?php

$__SUPERADMIN    = 0b00001110;
$__ADMIN         = 0b00000100;
$__AUDITEUR      = 0b00000010;
$__FORMATEUR     = 0b00000001;

function isSuperAdmin($role) {

    global $__SUPERADMIN;
    return $role & $__SUPERADMIN;

}

function isAdmin($role) {

    global $__ADMIN;
    return $role & $__ADMIN;

}

function isAuditeur($role) {

    global $__AUDITEUR;
    return $role & $__AUDITEUR;

}

function isFormateur($role) {

    global $__FORMATEUR;
    return $role & $__FORMATEUR;
    
}

// These are the methods to check if a user has the right privileges.
// Used to show/hide things in page, granting access to a certain content of the portal, ...

?>