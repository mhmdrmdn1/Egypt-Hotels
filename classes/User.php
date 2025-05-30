<?php

class User {
    private $pdo;
    private $roles = [];
    private $permissions = [];
    private $userId;

    public function __construct($pdo, $userId = null) {
        $this->pdo = $pdo;
        $this->userId = $userId;
        if ($userId) {
            $this->loadUserRoles($userId);
            $this->loadUserPermissions($userId);
        }
    }

    public function getAll($filters = [], $sort = [], $page = 1, $limit = 10) {
        $where = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $where[] = "status = ?";
            $params[] = $filters['status'] === 'active' ? 1 : 0;
        }
        
        if (!empty($filters['role'])) {
            $where[] = "role = ?";
            $params[] = $filters['role'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(name LIKE ? OR email LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        // Build WHERE clause
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        // Build ORDER BY clause
        $orderBy = "ORDER BY " . ($sort['field'] ?? 'created_at') . " " . 
                  (strtoupper($sort['direction'] ?? 'DESC'));
        
        // Calculate offset
        $offset = ($page - 1) * $limit;
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) FROM users $whereClause";
        $stmt = $this->pdo->prepare($countQuery);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();
        
        // Get users
        $query = "SELECT u.*, GROUP_CONCAT(r.name) as roles 
                 FROM users u 
                 LEFT JOIN user_roles ur ON u.id = ur.user_id 
                 LEFT JOIN roles r ON ur.role_id = r.id 
                 $whereClause 
                 GROUP BY u.id 
                 $orderBy 
                 LIMIT ? OFFSET ?";
        $stmt = $this->pdo->prepare($query);
        array_push($params, $limit, $offset);
        $stmt->execute($params);
        
        return [
            'users' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'pages' => ceil($total / $limit)
        ];
    }

    public function getStats() {
        $stats = [
            'total' => 0,
            'active' => 0,
            'disabled' => 0,
            'by_role' => []
        ];
        
        // Get total and status counts
        $query = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as disabled
        FROM users";
        
        $result = $this->pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        $stats['total'] = (int)$result['total'];
        $stats['active'] = (int)$result['active'];
        $stats['disabled'] = (int)$result['disabled'];
        
        // Get counts by role
        $query = "SELECT r.name, COUNT(ur.user_id) as count 
                 FROM roles r 
                 LEFT JOIN user_roles ur ON r.id = ur.role_id 
                 GROUP BY r.id, r.name";
        $result = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $stats['by_role'][$row['name']] = (int)$row['count'];
        }
        
        return $stats;
    }

    public function getRoles() {
        $stmt = $this->pdo->query("SELECT * FROM roles ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hasRole($role) {
        return in_array($role, $this->roles);
    }

    public function hasPermission($permission) {
        return in_array($permission, $this->permissions);
    }

    public function delete($userId) {
        $this->pdo->beginTransaction();
        try {
            // Delete user roles
            $stmt = $this->pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Delete user permissions
            $stmt = $this->pdo->prepare("DELETE FROM user_permissions WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Delete user
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updateLastLogin($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    private function loadUserRoles($userId) {
        $stmt = $this->pdo->prepare("
            SELECT r.name 
            FROM roles r 
            JOIN user_roles ur ON r.id = ur.role_id 
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$userId]);
        $this->roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function loadUserPermissions($userId) {
        // First, get role-based permissions
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT p.name 
            FROM permissions p 
            JOIN role_permissions rp ON p.id = rp.permission_id 
            JOIN user_roles ur ON rp.role_id = ur.role_id 
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$userId]);
        $rolePermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Then, get user-specific permissions
        $stmt = $this->pdo->prepare("
            SELECT p.name 
            FROM permissions p 
            JOIN user_permissions up ON p.id = up.permission_id 
            WHERE up.user_id = ?
        ");
        $stmt->execute([$userId]);
        $userPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Merge both sets of permissions
        $this->permissions = array_unique(array_merge($rolePermissions, $userPermissions));
    }

    public function getById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $params = [$email];
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function usernameExists($username) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    public function addUser($name, $email, $password, $role, $status, $username) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Add to users table
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, status, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $status, $username]);
        $userId = $this->pdo->lastInsertId();

        // If admin, add to admins table as well
        if ($role === 'admin') {
            $stmt = $this->pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);
            // ربط المستخدم بدور admin في جدول user_roles
            $roleStmt = $this->pdo->prepare("SELECT id FROM roles WHERE name = 'admin' LIMIT 1");
            $roleStmt->execute();
            $roleId = $roleStmt->fetchColumn();
            if ($roleId) {
                $linkStmt = $this->pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $linkStmt->execute([$userId, $roleId]);
            }
        }

        return $userId;
    }

    public function updateUser($userId, $name, $email, $password, $role, $status) {
        $this->pdo->beginTransaction();
        try {
            // 1. تحديث بيانات المستخدم في جدول users
            $query = "UPDATE users SET name = ?, email = ?, status = ?";
            $params = [$name, $email, $status];
            if ($password !== '') {
                $query .= ", password = ?";
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $params[] = $hashedPassword;
            }
            $query .= " WHERE id = ?";
            $params[] = $userId;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            // 2. إذا كان الدور admin، تحقق من وجود صف في جدول admins (باستخدام اسم المستخدم)؛ إذا لم يكن موجودًا، أضف صفًا جديدًا (باستخدام اسم المستخدم وكلمة المرور المشفرة)
            if ($role === 'admin') {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = (SELECT username FROM users WHERE id = ?)");
                $stmt->execute([$userId]);
                if ($stmt->fetchColumn() == 0) {
                    $stmt = $this->pdo->prepare("INSERT INTO admins (username, password) SELECT username, password FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                }
            }

            // 3. تحديث الدور في جدول user_roles (حذف الأدوار القديمة ثم إضافة الدور الجديد)
            $stmt = $this->pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $stmt->execute([$userId]);
            $stmt = $this->pdo->prepare("SELECT id FROM roles WHERE name = ? LIMIT 1");
            $stmt->execute([$role]);
            $roleId = $stmt->fetchColumn();
            if ($roleId) {
                $stmt = $this->pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $stmt->execute([$userId, $roleId]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
} 