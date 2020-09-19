---
difficulty: hard
keywords:
  - lisp
  - algorithms
answer:
  pattern: '\b0*7\b'
---
# Short Answer

In the following lisp code, how many steps are required to solve the problem?

```lisp
(defun Hanoi (n origin destination auxiliar)
(if (= n 1) (moure 1 origin destination)
    (progn (Hanoi (- n 1) origin auxiliar destination)
        (moure n origin destination)
        (Hanoi (- n 1) auxiliar destination origin))))

(defun moure (k origin destination)
    (print (list 'move 'disk k
        'from 'column origin
        'to 'column destination)))

(Hanoi 3 'left 'right 'middle)
```

# Explanation

Here you can explain your answer
