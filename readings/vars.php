<html>
<head>
<link rel="stylesheet" href="dextras.css" />

<script type="text/x-mathjax-config">
    // <![CDATA[
    MathJax.Hub.Config({
        TeX: {extensions: ["AMSmath.js", "AMSsymbols.js"]},
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        showProcessingMessages : false,
        messageStyle : "none" ,
        showMathMenu: false ,
        tex2jax: {
            processEnvironments: true,
            inlineMath: [ ['$','$'] ],
            displayMath: [ ['$$','$$'] ],
            preview : "none",
            processEscapes: true
        },
        "HTML-CSS": { linebreaks: { automatic:true, width: "latex-container"} }
    });
    // ]]>
 // MathJax.Hub.Queue(reportReload);
</script>
<script type="text/javascript" src="MathJax.js"></script>
<script type="text/javascript" src="latex2html5.min.js"></script>


</head>
<body>
<HR>
<a class=subhead href=#contents name=contents>Contents</span>

<UL>
  <LI><A HREF="#intro">Introduction</A>
  <LI><A HREF="#locals">Local Variables</A>
  <UL>
    <LI><A HREF="#doubles">Doubles</A>
    <LI><A HREF="#youtry1">Test Yourself #1</A>
  </UL>
  <LI><A HREF="#globals">Global Variables</A>
  <LI><A HREF="#nonLocals">Non-Local Variables</A>
    <UL>
      <LI><A HREF="#static">Static Scoping</A>
      <UL>
        <LI><A HREF="#youtry2">Test Yourself #2</A>
        <LI><A HREF="#accessLink">Method #1: Access Links</A>
        <UL>
          <LI><A HREF="#youtry3">Test Yourself #3</A>
          <LI><A HREF="#summary">Summary</A>
        </UL>
        <LI><A HREF="#display">Method #2: The Display</A>
        <UL>
          <LI><A HREF="#youtry4">Test Yourself #4</A>
        </UL>
        <LI><A HREF="#compare">Comparison: Access Links vs The Display</A>
      </UL>
      <LI><A HREF="#dynamic">Dynamic Scoping</A>
      <UL>
        <LI><A HREF="#deep">Deep Access</A>
        <UL>
          <LI><A HREF="#youtry5">Test Yourself #5</A>
        </UL>
        <LI><A HREF="#shallow">Shallow Access</A>
        <UL>
          <LI><A HREF="#youtry6">Test Yourself #6</A>
        </UL>
      </UL>
  </UL>
</UL>

<P>
<a class=subhead href=#intro name=intro>Introduction</a>
<P>
In this set of notes we will consider how three different kinds of
variables: local, global, and non-local, are accessed at runtime.
For the purposes of this discussion, we will define these three
categories as follows:
<OL>
  <LI> <b>Local variables</b>: variables that are declared in the method
       that accesses them.
  <LI> <b>Global variables</b>: variables that are not declared in any
       method, but are accessible in all methods.  For example,
       the public static fields of a Java class can be used in any
       method; variables can be declared at the file level
       in a C or C++ program, and used in any function;
       variables can be declared in the outermost scope of a Pascal program
       and can be used in any procedure or function.
  <LI> <b>Non-local variables</b>: we will use this term for two situations:
   <OL>
     <LI> In languages that allow sub-programs to be <em>nested</em>, it
       refers to variables that are declared in one sub-program and used in
       a nested sub-program.  This is allowed, for example, in Pascal.
     <LI> In languages with dynamic scope, it refers to variables that
       are used in a method without being declared there (so the use
       corresponds to the declaration in the "most recently called,
       still active" method).
   </OL>
</OL>
Our discussion will include information specific to the last programming
assignment (the code generator);
i.e., how to generate MIPS code to access these three kinds of variables.
<P>
<a class=subhead href=#locals name=locals>Local Variables</a>
<P>
Local variables (including parameters) are stored in the activation
record of the method in which they are declared.
They are accessed at runtime using an offset from the frame pointer (FP).
Since we're assuming that "up" in the stack means a <em>lower</em>
address, these offsets will be negative numbers.
For example, given this code:
<pre>
        void P(int x) {
          int a, b;
	  ...
	}
</pre>
and assuming that activation records do not include an access link field
or space for saved registers,
P's activation records will be organized as follows:
<pre>
                              <--- Stack Pointer
          |               |
          |_______________|                
       b: |               |
          |_______________|
       a: |               |
          |_______________|
          | Control Link  |
          |_______________|
          | Return Addr   |
          |_______________|
       x: |               |                
          |_______________|   <--- Frame Pointer
</pre>
Assuming 4 bytes for each pointer and each integer, here are the offsets
for each of P's locals:
<UL>
  <LI> x: offset 0
  <LI> a: offset -12
  <LI> b: offset -16
</UL>
The following MIPS code loads the values of a and b into
registers t1 and t2, respectively:
<UL>
<pre>
lw $t1, -12($fp)  # load a
lw $t2, -16($fp)  # load b
</pre>
</UL>
To be able to generate this code at compile time (e.g., to process
a statement like <tt>x = a + b</tt>), the offset of each local variable
must be known by the compiler.
Therefore, the offset of each local variable from the Frame Pointer
should be stored as an attribute of the variable in the symbol table.
This can be done as follows:
<UL>
  <LI> Keep track of the current offset in a global variable (e.g., a static
       ASTnode field) or in the symboltable (i.e., add a new field
       to the SymTab class, with methods to set and get the field's value).
  <LI> When the symbol-table-building code starts processing a method,
       set the current offset to zero.
  <LI> For each parameter: add the name to the symboltable as usual, but
       also add the value of the current offset as an attribute,
       and update the current offset by subtracting the size of the parameter
       (which will depend on its type).
  <LI> After processing all parameters, subtract 8 from the current offset
       (to leave room for the return address and the control link).
  <LI> For each local variable, add the name to the symboltable with the
       current offset, and subtract the appropriate amount from the
       current offset.
</UL>
Note that the "current offset" is only set to zero at the start
of a method, <em>not</em> at the start of a nested block, so each variable
declared (somewhere) in the method has its own, unique offset in that
method's AR.
<P>
<a class=subhead href=#doubles name=doubles>Doubles</a>
<P>
For MIPS code, each double variable requires 8 bytes, and its offset
is in the "middle" of those bytes.
For example, given this code:
<pre>
        void P(double x) {
          double a, b;
	  ...
	}
</pre>
variable x will be stored in the bytes at offsets 0 to -7 from the
Frame Pointer, and the offset that should be stored in x's symboltable
entry (and used to access x at runtime) will be 0.
Here are the offsets for all of P's locals:
<UL>
  <LI> x: offset -0
  <LI> a: offset -16
  <LI> b: offset -24
</UL>
Special "double" registers (e.g., F0, F2, F12)
must be used for double variables at runtime, and
there are special opcodes for operations on double values.
The following MIPS code loads the values of a and b into
registers F0 and F2, respectively:
<UL>
<pre>
l.d $f0, -20($fp)  # load a
l.d $f2, -28($fp)  # load b
</pre>
</UL>
<hr>
<P>
<a name=youtry1>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
Assume that both an address and an integer require 4 bytes of
storage, and that a double requires 8 bytes.
Also assume that each activation record includes parameters, a return
address, a control link, and space for local variables as illustrated
above.
What are the offsets (in bytes) for each of the parameters and
local variables in the following functions?
<UL>
<pre>
void P1(int x, int y) {
   int a, b, c;
   ...
   while (...) {
      double a, w;
   }
}

void P2() {
   int x, y;
   ...
   if (...) {
      double a;
      ...
   }
   else {
      int b, c;
      ...
   }
}
</pre>
</UL>
<p><a href="SOLUTIONS/RUNTIME-ACCESS-ANSWERS.html#ans1">solution</a></p>
<hr>
<P>
<a class=subhead name=globals href=#globals>Global Variables</a>
<P>
As noted above, global variables are stored in the static data area.
Using MIPS code, each global is stored in space labeled with the name
of the variable, and the variable is accessed at runtime using its name.
(Since we will be using the SPIM simulator, and since SPIM has some
reserved words that can also be used as Simple variable names, you
will need to add an underscore to global variable names to prevent
clashes with SPIM reserved words.)
<P>
For example, if the source code includes:
<pre>
       // global variables
       int g;
       double d;
</pre>
The following code would be generated to reserve space in the static
data area for variables g and d:
<pre>
                .data          # put the following in the static data area
		.align 2       # align on a word boundary
        _g:     .space 4       # set aside 4 bytes
	        .data
		.align 2
        _d:     .space 8
</pre>
And the following code would be generated to load the value of variable g
into register t0, and to load the value of variable d into register f0:
<pre>
                lw    $t0, _g       # load contents of g into t0
		l.d   $f0, _dd      # load contents of dd into f0
</pre>

<a class=subhead href=#nonLocals name=nonLocals>Non-Local Variables</a>
<P>
Recall that we are using the term "non-local variable" to refer
to two situations:
<OL>
  <LI> In statically-scoped languages that allow nested sub-programs,
       a variable can be declared in one sub-program and accessed in
       another, nested sub-program.  Such variables are "non-local"
       in the nested sub-program that accesses them.
       These variables are stored in the activation record of the
       sub-program that declares them.  When they are used as non-local
       variables, that activation record is found <b>at runtime</b> either
       using <b>access links</b> or a <b>display</b> (discussed below).

  <LI> In dynamically-scoped languages, a variable can be declared in
       one method, and accessed in a called method.  The variable is non-local
       in the method that accesses it.
       Two different approaches to supporting the use of non-local
       variables in dynamically-scoped languages are discussed below.
</OL>
Note that in languages (like C and C++) that permit the same name to be
used in nested blocks within a method, we might also use the term "non-local
variable" to refer to a use of a variable in one block that was declared in
an enclosing block.
However, this is not an interesting case in terms of runtime access.
All of a method's variables (regardless of which block they are declared in)
are stored in the method's activation record, and are accessed using
offsets from the frame pointer, as discussed above.
<P>
<a class=subhead name=static href=#static>Static Scoping</a>
<P>
First, let's consider an example (Pascal) program that includes accesses to
non-local variables (the nesting levels of the procedures are given for
later reference):
<pre>
   + program MAIN;
   | var <font color=red>x</font>: integer;
   |
   |	+ procedure P;
   |   (2)  write(<font color=red>x</font>);
   |    +
   |   
   |	+ procedure Q;
   |    | var <font color=blue>y</font>: integer = <font color=red>x</font>;
   |    |
   |	|    + procedure R;
   |	|    |   <font color=red>x</font> = <font color=red>x</font> + 1;
   |    |    |   <font color=blue>y</font> = <font color=blue>y</font> + <font color=red>x</font>;
  (1)  (2)  (3)  if <font color=blue>y</font>&lt;6 call R;
   |	|    |   call P
   |    |    +
   |    |     
   |	|    call R;
   |	|    call P;
   |    |    if <font color=red>x</font> < 5 call Q;
   |    +
   |    
   |    <font color=red>x</font> = 2;
   |    call Q;
   +
</pre>
<p><a href="SOLUTIONS/RUNTIME-ACCESS-ANSWERS.html#ans2">solution</a></p>
<hr>
<P>
<a name=youtry2>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Trace the execution of this program, drawing the activation records
for the main program and for each called procedure (include only local
variables and control links in the activation records).
Each time a non-local variable is accessed, determine which activation
record it is stored in.
Notice that the relative nesting levels of the variable's declaration and
its use does <b>not</b> tell you how far down the stack to look for
the activation record that contains the non-local variable.
In fact, this number changes for different calls (e.g., due to recursion).
<p><a href="SOLUTIONS/RUNTIME-ACCESS-ANSWERS.html#ans2">solution</a></p>
<hr>
<P>
<a class=subsubhead name=accessLink href=#accessLink>Method #1: Access Links</a>
<P>
The idea behind the use of access links is as follows:
<UL>
  <LI> Add a new field to each AR -- the <b><em>access link</em></b> field.
  <LI> If P is (lexically) nested inside Q, then at runtime, P's AR's
       access link will point to the access link field in the AR of the
       most recent activation of Q.
  <LI> Therefore, at runtime, access links will form a chain corresponding
       to the nesting of sub-programs.
       For each use of a non-local x:
       <UL>
       <LI> At compile time, use the "Level Number" attribute of x and
            the "Current Level Number" (of the sub-program that is accessing
	    x) to determine how many links of the chain to follow at runtime.
       <LI> If P at level i uses variable x, declared at level j, follow
	    i-j links, then use x's "Offset" attribute to find x's
	    storage space inside the AR.
	    </UL>
</UL>
Here's a snapshot of the stack when the example program given above is running,
after the first call from R to P (showing only the access links and
the local variables in the ARs):
<pre>
			____________
	          P	|          |------------+   <-- FP
			+==========+            |
	          R	|          |----------+ |
			+==========+          | |
                  R    	|          |------+   | |
			+==========+      |   | |
		      y:|    9     |      |   | |
		  Q     +----------|      |   | |
	          	|          |--+ <-+ <-+ |
			+==========+  |         |
		      x:|    4     |  |         |
		MAIN	|----------|  |         |
		        |   null   |<-+      <--+
			|__________|  
</pre>
To access the value of x from procedure R, access links must be followed.
The number of links that must be followed is:
<UL>
(level # of R) - (level # of decl of x)<br>
= 3 - 1 = 2
</UL>
So the code for <tt>y = x</tt> in procedure R would be as shown below
(Note: we assume that the access link field is the first field in the
AR; i.e., is pointed to by the FP.
We also assume that both variable x and variable y are at offset -12 in
their ARs.
This is because the access link is at offset 0, the return address is at
offset -4, and the control link is at -8; since x and y are the first local
variables in their respective program/procedure, they are each at offset
-12 in their respective ARs.)
<UL>
<pre>
        move $t0, FP     // no links followed yet; t0 holds ptr to R's access link field
        lw $t0, ($t0)     // 1 link: t0 holds ptr to Q's AR's access link field
 +->    lw $t0, ($t0)     // 2 links: t0 holds ptr to main's AR's access link field
 |      lw $t0, -12($t0)  // t0 holds value of x
 |      sw $t0, -12(FP)  // y = x
 |
This code would be repeated if we needed to follow more links.
</pre>
</UL>
How to set up access links:
<UL>
  <LI>The link is set up by the <em>calling</em> procedure.
  <LI> How to set up the link depends on the relative nesting levels of the
       calling and called procedures.
</UL>
There are two cases:
<br><b>Case 1:</b>
The calling procedure's level is less than the called procedure's level
(i.e., the called procedure is nested <em><b>directly</b></em> inside
the calling procedure, because if it's not, it's invisible and it can't
be called).
In this case, the called procedure's access link should point to the access
link field of the calling procedure's AR.
This case occurs in the example when Q calls R.
<P>
In this case, the calling procedure sets up the called procedure's access link
by pushing the value of the
FP just before the call, since the FP points to its access link. 
For example, when Q is calling R, here is a picture of the stack just
after R's access link has been set up by Q:
<pre>
                                             <-- SP
			|----------|        
			|	   |------+
			|==========|      |     
		      y:|          |      |     
		  Q     |----------|      |     
                        |          |--+ <-+  <-- FP
                        |==========|  |
		      x:|          |  |        
		MAIN	|----------|  |        
			|   null   |<-+
			|__________|  
</pre>
<b>Case 2:</b>
The calling procedure's level is greater than or equal to the called
procedure's level.
In this case, the called procedure's access link should point to an AR that
is already on the stack.
The value for the new access link is found by starting with the value of
the calling procedure's access link field (which is pointed to by the FP),
and following X-Y access links, where:
<UL>
X = calling procedure's level<BR>
Y = called procedure's level
</UL>
The following code loads the value of the calling procedure's access link
field into register t0:
<UL>
<pre>
lw    $t0, 0(FP)
</pre>
</UL>
If X == Y (i.e., no links should be followed to find the value of the
new access link), then the value of <tt>t0</tt> should simply be pushed
onto the stack.
If X is greater than Y, then the code:
<UL>
<pre>
lw    $t0, 0($t0)
</pre>
</UL>
should be generated X - Y times, before pushing the value of <tt>t0</tt>.
<P>
To illustrate this situation, consider two cases from the example code:
R calls P, and Q calls itself.
Recall that the nesting structure of the example program is:
<pre>
      +--
      |
      |   +--
      |   |
      |  P|
      |   |
      |   +--
      |
MAIN  |   +--
      |   |
      |   |     +--
      |   |	|
      |  Q|    R|
      |   |	|
      |   |	+--
      |   |
      |   +--
      |
      +--
</pre>
When R is about to call P, the stack looks like this:
<pre>
			+==========+            
	          R	|          |----------+ 
			+==========+          | 
                  R    	|          |------+   | 
			+==========+      |   | 
		      y:|    9     |      |   | 
		  Q     +----------|      |   | 
	          	|          |--+ <-+ <-+ 
			+==========+  |         
		      x:|    4     |  |         
		MAIN	|----------|  |         
		        |   null   |<-+      
			|__________|  
</pre>
Since P is nested inside MAIN, P's access link should point to MAIN's AR
(i.e., the bottom AR on the stack).
R's nesting level is 3 and P's nesting level is 2.
Therefore, we start with the value of R's access link (the pointer to Q's
AR) and follow <em>one</em> link.
This retrieves the values of Q's access link, which is (as desired) a
pointer to MAIN's AR.
This is the value that will be pushed onto the stack (copied into P's AR
as its access link).
<P>
When Q is about to call itself, the stack looks like this:
<pre>
			|==========|      
		      y:|          |           
		  Q     |----------|           
	          	|          |--+        
			|==========|  |    
		      x:|          |  |   
		MAIN	|----------|  |   
		        |   null   |<-+
			|__________|  
</pre>
The access link in the new AR for Q should be the same as the access link
in the current AR for Q; namely, it should be a pointer to the AR for MAIN.
This value is found by starting with the value of Q's access link (a pointer
to MAIN's AR) and following <em>zero</em> links (because X = Y = 1).
<hr>
<P>
<a name=youtry3>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
Trace the execution of the example program again.
Each time a procedure is called, determine which case applies in terms
of how to set up the called procedure's access link.
Then use the appropriate algorithm to find the value of the new access
link, and draw the new AR with its access link.
<P>
Each time a non-local variable x is accessed, make sure that you find
its activation record by following i - j access links (where i is the nesting
level of the procedure that uses x
and j is the nesting level of the procedure that declares x).
<p><a href="SOLUTIONS/RUNTIME-ACCESS-ANSWERS.html#ans3">solution</a></p>
<hr>
<P>
<a class=subsubhead name=summary>Access Links: Summary</a>
<P>
To <em><b>use</b></em> an access link:
<UL>
Follow i - j links to find the AR with space for non-local x,
where i is the nesting level of the procedure that uses x and j is the
nesting level of the procedure that declares x.
</UL>
To <em><b>set up</b></em> an access link:
<UL>
  <LI> case #1: Calling procedure's level is less than called procedure's
       level: Push the value of the FP to be the access link field of the
       called procedure's AR.
  <LI> case #2: Calling procedure's level is greater than or equal to the
       called procedure's level: Find the value to push by starting with
       the value of the calling procedure's access links, then following
       X-Y links, where X = calling procedure's level, and
       Y = called procedure's level.
</UL>		    
<P>
<a class=subsubhead name=display href=#display>Method #2: The Display</a>
<P>
The motivation for using a display is to avoid the runtime overhead of
following multiple access links to find the activation record that
contains a non-local variable.
The idea is to maintain a global "array" called the <em><b>display</b></em>.
Ideally, the display is actually implemented using registers (one for each
array element) rather than an actual array;
however, it can also be implemented using an array in the static data area.
<P>
The size of the display is the maximum nesting depth of a procedure in the
program (which is known at compile time).
The display is used as follows:
<UL>
  <LI> When procedure P at nesting level k is executing,
       DISPLAY[0],...DISPLAY[k-2] hold pointers to the
       ARs of the most recent activations of the k-1 
       procedures that lexically enclose P.
       DISPLAY[k-1] holds a pointer to P's AR.
  <LI> To access a non-local variable declared at level x, use DISPLAY[x-1]
       to get to the AR that holds x, then use the usual offset 
       to get x itself.
</UL>
To illustrate this, refer back to our running example program, outlined below:
<pre>
      +--
      |int x;
      |
      |   +--
      |   |
      |  P|...x...
      |   |
      |   +--
      |
MAIN  |   +--
      |   | int y;
      |	  |
      |   |     +--
      |   |	|...x...y...
      |  Q|    R|call R
      |   |	|call P
      |   |	+--
      |   |
      |   | call R
      |   | call P
      |   | if (...) call Q
      |   +--
      |
      | call Q
      +--
</pre>
Below are two pictures comparing the use of access links with the use
of a display.
Both show the same moment at runtime.
<pre>
        USING ACCESS LINKS                  USING A DISPLAY


         ____________                          ----------
   P     |          |------------+	   P   |        |<--+
         |==========|            |	       |========|   |   +--+
   R     |          |----------+ |	   R   |        |<------|  | [2]
         |==========|          | |             |========|   |   +--+
       y:|          |          | |           y:|        |   +---|  | [1]
   Q     |----------|          | |         Q   |--------|       +--+
         |          |------+ <-+ |             |        |   +---|  | [0]
         |==========|      |     |             |========|   |   +--+
       x:|          |      |     |           x:|        |   | DISPLAY
 MAIN    |----------|      |     |       MAIN  |--------|   |
         |   null   |    <-+  <--+             |        |<--+
         |__________|                          ----------

	    STACK                                STACK   

</pre>
<P>
To maintain the display, a new field (called the "save-display" field)
is added to each activation record.
The display is maintained as follows:
<UL>
  <LI> When a procedure at nesting level k is called:
  <UL>
    <LI> The current value of DISPLAY[k-1] is saved in the "save-display"
         field of the new AR.
    <LI> DISPLAY[k-1] is set to point to (the save-display field of) the new
         AR.
  </UL>
  <LI> When the procedure returns, DISPLAY[k-1] is restored using the value
       saved in the "save-display" field (of the returning procedure).
</UL>
This process is illustrate below, showing how the display and the save-display
fields are updated when R calls P (only the local variable and save-display
fields of the ARs are shown).
<pre>
        Before R calls P:               
        ----------------

              ________                  ____________
          [2] |      |--\            R  |          |
              |______|   -------------->|          |
          [1] |      |--\               |==========|
              |______|   \            y:|          |
          [0] |      |--\ \          Q  |----------|
              |______|   \ ------------>|          |
                          \             |==========|
                           \          x:|          |
                            \     MAIN  |----------|
                             ---------->|          |  
                                        |==========|


        After R calls P:                
        ---------------

                        
              ________                 
          [2] |      |--\               ------------
              |______|   \          P   |          |---+
          [1] |      |----\------------>|          |   |
              |______|     \            |==========|   |
          [0] |      |--\   \       R   |          |   |  
              |______|   \   ---------->|          |   |
                          \             |==========|   |
                           \          x:|          |   |
                            \       Q   |----------|   |
                             \          |          |<--+
                              \         |==========|
                               \      y:|          |
                                \  MAIN |----------|
                                 ------>|          |
				        |----------|
</pre>
<hr>
<P>
<a name=youtry4>
<u><b>TEST YOURSELF #4</b></u>
</center></a>
<P>
Trace the execution of the running example program, assuming that a display
is used instead of access links.
Each time a non-local variable x is accessed, make sure that you
understand how to find its AR using the display.
<hr>
<P>
<a class=subsubhead name=compare href=#compare>Comparison: Access Links vs The Display</a>
<P>
<UL>
  <LI> Access links can require more time (at runtime) to access 
     non-locals (especially when the non-local is many nesting
     levels away).
  <LI> It can also require more time to set up a new access link
     when a procedure is called (if the nesting level of the called
     procedure is much smaller than the nesting level of the calling
     procedure).
  <LI> Displays require more space (at runtime).
  <LI> If the compiler is flexible enough to implement the display
       using registers if enough are available, or using space in the
       static data area, then the compiler code itself may be rather
       complicated (and error-prone).
  <LI> In practice, sub-programs usually are not very deeply nested, so
       the runtime considerations may not be very important.
</UL>
<P>
<a class=subhead name=dynamic href=#dynamic>Dynamic Scoping</a>
<P>
Recall that under dynamic scoping, a use of a non-local variable corresponds
to the declaration in the "most recently called, still active" method.
So the question of which non-local variable to use can't be determined at
compile time. It can only be determined at run-time.
There are two ways to implement access to non-locals under dynamic scope:
"deep access" and "shallow access", described below.
<P>
<a class=subsubhead name=deep href=#deep>Deep Access</a>
<P>
Using this approach, given a use of a non-local variable, control links
are used to search back in
the stack for the most recent AR that contains space for that variable.
Note that this requires that it be possible to tell which variables are
stored in each AR;
this is more natural for languages that are <em>interpreted</em> rather
than being compiled (which was indeed the case for languages that used
dynamic scope).
Note also that the number of control links that must be followed cannot
be determined at compile time;
in fact, a different number of links may be followed at different times
during execution, as illustrated by the following example program:
<UL>
<pre>
void P() { write x; }

void Q() {
  x = x + 1;
  if (x < 23) Q();
  else P();
}

void R() {
  int x = 20;
  Q();
  P():
}

void main() {
  int x = 10;
  R();
  P();
}
</pre>
</UL>
<hr>
<P>
<a name=youtry5>
<u><b>TEST YOURSELF #5</b></u>
</center></a>
<P>
Trace the execution of the program given above.
Note that method P includes a use of non-local variable x.
How many control links must be followed to find the AR with space
for x each time P is called?
<hr>
<P>
<a class=subsubhead name=shallow href=#shallow>Shallow Access</a>
<P>
Using this approach,
space is allocated (in registers or in the static data area) for every
variable name that is in the program (i.e., one space for variable x
even if there are several declarations of x in different methods).
For every reference to x, the generated code refers to the <em>same</em>
location.
<P>
When a method is called, it saves, in its own AR, the current values
of all of the variables that it declares itself (i.e., if it declares
x and y, then it saves the values of x and y that are currently in the
space for x and y).  It restores those values when it finishes.
<P>
Note that this means that when a method accesses a <em>non-local</em>
variable x, the value of x from the most-recently-called,
still-active method is stored in the (single) location for x.
There is no need to go searching down the stack!
<P>
<a name=youtry6>
<u><b>TEST YOURSELF #6</b></u>
</center></a>
<P>
<b>Question 1:</b>
Trace the execution of the program given above again, this time assuming
that shallow access is used.
<P>
<b>Question 2:</b>
What are the advantages and disadvantages of shallow access compared
with deep access?  (Consider both time and space requirements.)
<hr>
