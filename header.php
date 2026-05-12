<header class="top-bar">
    <div class="user-info">
        <span class=" d-flex align-items-center flex-column">Hola, <strong>
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </strong>
        </span><span>
            <?php echo htmlspecialchars($_SESSION['rut']); ?>
        </span>
        <div class="avatar">
            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
        </div>

    </div>
</header>