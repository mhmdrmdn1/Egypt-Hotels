-- Assign admin role to the existing admin user
INSERT INTO user_roles (user_id, role_id)
SELECT a.id, r.id
FROM admins a
CROSS JOIN roles r
WHERE r.name = 'admin'
AND a.username = 'mhmd_rmdn_1'
ON DUPLICATE KEY UPDATE user_id = user_id;

-- Grant all permissions directly to the admin user as well
INSERT INTO user_permissions (user_id, permission_id)
SELECT a.id, p.id
FROM admins a
CROSS JOIN permissions p
WHERE a.username = 'mhmd_rmdn_1'
ON DUPLICATE KEY UPDATE user_id = user_id; 