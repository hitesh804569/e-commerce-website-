<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'FAQ - ' . SITE_NAME;

// Get FAQs
$faqs = fetchAll("SELECT * FROM faqs WHERE status = 'active' ORDER BY display_order, id");

include __DIR__ . '/../includes/header.php';
?>

<style>
    .faq-container {
        padding: 3rem 0;
    }
    
    .faq-item {
        background: var(--card-bg);
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .faq-question {
        padding: 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        transition: all 0.3s;
    }
    
    .faq-question:hover {
        background: var(--light-bg);
    }
    
    .faq-answer {
        padding: 0 1.5rem 1.5rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }
    
    .faq-icon {
        transition: transform 0.3s;
    }
    
    .faq-item.active .faq-icon {
        transform: rotate(180deg);
    }
</style>

<div class="container faq-container">
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">Frequently Asked Questions</h1>
        <p class="text-muted">Find answers to common questions about our service</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span><?php echo sanitize($faq['question']); ?></span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer" style="<?php echo $index === 0 ? '' : 'display: none;'; ?>">
                        <?php echo nl2br(sanitize($faq['answer'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No FAQs available at the moment.</p>
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-5">
                <h4 class="fw-bold mb-3">Still have questions?</h4>
                <p class="text-muted mb-4">Can't find the answer you're looking for? Please contact our support team.</p>
                <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope me-2"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFaq(element) {
        const faqItem = element.parentElement;
        const answer = faqItem.querySelector('.faq-answer');
        const isActive = faqItem.classList.contains('active');
        
        // Close all FAQs
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
            item.querySelector('.faq-answer').style.display = 'none';
        });
        
        // Toggle current FAQ
        if (!isActive) {
            faqItem.classList.add('active');
            answer.style.display = 'block';
        }
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
