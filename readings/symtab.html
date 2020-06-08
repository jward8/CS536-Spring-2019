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
<A NAME=top></a>

<HR>
<H2>Contents</H2>

<UL>
  <LI><A HREF="#intro">Introduction</A>
  <LI><A HREF="#symTab">Symbol Tables</A>
    <UL>
    <LI><A HREF="#scope">Scoping</A>
    <LI><A HREF="#youtry1">Test Yourself #1</A>
    <LI><A HREF="#youtry2">Test Yourself #2</A>
    <LI><A HREF="#imp">Symbol Table Implementations</A>
      <UL>
      <LI><A HREF="#list">Method 1: List of Hashtables</LI>
        <UL>
	<LI><A HREF="#youtry3">Test Yourself #3</A>
	</UL>
      <LI><A HREF="#symtabOfLists">Method 2: Hashtable of Lists</LI>
        <UL>
	<LI><A HREF="#youtry4">Test Yourself #4</A>
	</UL>
      </UL>
    </UL>
  <LI><A HREF="#typeCheck">Type Checking</A>
    <UL>
      <LI><A HREF="#youtry5">Test Yourself #5</A>
    </UL>
</UL>

<P>
<a name=intro></a>
<span class=subhead>Introduction</span>
<P>
The parser ensures that the input program is syntactically correct,
but there are other kinds of correctness that it cannot (or usually
does not) enforce.
For example:
<UL>
  <LI> A variable should not be declared more than once in the same scope.
  <LI> A variable should not be used before being declared.
  <LI> The type of the left-hand side of an assignment should match the
       type of the right-hand side.
  <LI> Methods should be called with the right number and types of arguments.
</UL>
<P>
The next phase of the compiler after the parser, sometimes called the
<b>static semantic analyzer</b> is in charge of checking for these kinds of errors.
The checks can be done in two phases, each of which involves traversing
the abstract-syntax tree created by the parser:
<OL>
  <LI> For each scope in the program: Process the declarations, adding new
       entries to the symbol table and reporting any variables that are
       multiply declared;
       process the statements, finding uses of undeclared variables, and
       updating the "ID" nodes of the abstract-syntax tree to point to the
       appropriate symbol-table entry.
  <LI> Process all of the statements in the program again, using the
       symbol-table information to determine the type of each expression,
       and finding type errors.
</OL>
Below, we will consider how to build symbol tables and how to use them to
find multiply-declared and undeclared variables.
We will then consider type checking.
<P>
<a name=symTab></a>
<span class=subhead>Symbol Tables</span>
<P>
The purpose of the symbol table is to keep track of names declared in the
program.
This includes names of classes, fields, methods, and variables.
Each symbol table entry associates a set of attributes with one name;
for example:
<UL>
  <LI> which kind of name it is
  <LI> what is its type
  <LI> what is its nesting level
  <LI> where will it be found at runtime.
</UL>
One factor that will influence the design of the symbol table is
what <b>scoping rules</b> are defined for the language being compiled.
Let's consider some different kinds of scoping rules before continuing
our discussion of symbol tables.
<P>
<a name=scope></a>
<span class=subsubhead>Scoping</span>
<P>
A language's scoping rules tell you when you're allowed to reuse
a name, and how to match uses of a name to the corresponding
declaration.
In most languages, the same name can be declared multiple times
under certain circumstances.
In Java you can use the same name in more than one
declaration if the declarations involve different <i>kinds</i> of names.
For example, you can use the same name for a class,
a field of the class, a method of the class, and a local variable
of the method (this is not recommended, but it is legal):
<UL>
<pre>
class Test {
  int Test;
  
  void Test(  ) {
      double Test;  // could also be declared int
  }
}
</pre>
</UL>
<P>
In Java (and C++), you can also use the same name for more than one
method as long as the number and/or types of parameters are unique
(this is called <i>overloading</i>).
<P>
In C and C++, but not in Java, you can declare variables with the same
name in different blocks.
A block is a piece of code inside curly braces;
for example, in an <b>if</b> or a loop.
The following is a legal C or C++ function, but not a legal Java method:
<UL>
<pre>
void f(int k) {
   int x = 0;       /* x is declared here */

   while (...) {
      int x = 1;   /* another x is declared here */
      ...
      if (...) {
        float x = 5.5;  /* and yet another x is declared here! */
        ...
      }
   }
}
</pre>
</UL>
As mentioned above, the scopinge rules of a language determine which
declaration of a named object corresponds to each use.
C, C++, and Java use what is called <b>static scoping</b>;
that means that the correspondence between uses and declarations is
made at compile time.
C and C++ use the "most closely nested" rule to match nested declarations
to their uses: a use of variable x matches the declaration in the
most closely enclosing scope such that the declaration precedes the use.
In C and C++, there is one, outermost scope that includes
the names of the global variables (the variables that are declared
outside the functions) and the names of the functions that are
not part of any class.
Each function has one or more scopes.
Both C and C++ have one scope for the parameters and the "top-level"
declarations, plus one for each block in the function (delimited by curly
braces).
In addition, C++ has a scope for each <em>for</em> loop:
in C++ (but not in C) you can declare variables in the for-loop
header.
<P>
In the example given above, the outermost scope includes just the name "f",
and function f itself has three scopes:
<OL>
  <LI> The outer scope for f includes parameter k, and the variable x
       that is initialized to 0.
  <LI> The next scope is for the body of the <b>while</b> loop,
       and includes the variable x that is initialized to 1.
  <LI> The innermost scope is for the body of the <b>if</b>, and includes
       the variable x that is initialized to 5.5.
</OL>
So a use of variable x inside the loop but not inside the <b>if</b>
matches the declaration
in the loop (has the value 1), while a use of x outside the loop
(either before or after the loop) matches the declaration at the
beginning of the function (has the value 0).
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
<b>Question 1:</b>
Consider the names declared in the following code.
For each, determine whether it is legal according to the rules used in Java.
<UL>
<pre>
class animal {
  // methods
  void attack(int animal) {
     for (int animal=0; animal<10; animal++) {
         int attack;
     }
  }

  int attack(int x) {
     for (int attack=0; attack<10; attack++) {
        int animal;
     }
  }

  void animal() { }

  // fields
  double attack;
  int attack;
  int animal;
}
</pre>
</UL>
<P>
<b>Question 2:</b>
Consider the following C++ code.
For each use of a name, determine which declaration it corresponds to (or
whether it is a use of an undeclared name).
<UL>
<pre>
int k=10, x=20;

void foo(int k) {
    int a = x;
    int x = k;
    int b = x;
    while (...) {
        int x;
	if (x == k) {
	   int k, y;
	   k = y = x;
	}
	if (x == k) {
	   int x = y;
	}
    }
}
</pre>
</UL>
<p><a href="SOLUTIONS/SYMTAB-ANSWERS.html#ans1">solution</a></p>
<hr>
<P>
Not all languages use static scoping.
Lisp, APL, and Snobol use what is called <b>dynamic</b> scoping.
A use of a variable that has no corresponding declaration in the same function
corresponds to the declaration in the <b>most-recently-called
still active</b> function.
For example, consider the following code:
<UL>
<pre>
void main() {
  f1();
  f2();
}

void f1() {
  int x = 10;
  g();
}

void f2() {
  String x = "hello";
  f3();
  g();
}

void f3() {
  double x = 30.5;
}

void g() {
  print(x);
}
</pre>
</UL>
Under dynamic scoping this program outputs "10 hello".
The first call to g comes from f1, whose copy of x has value 10.
The next call to g comes from f2.
Although f3 is called by f2 before it calls g, the call to f3 is not
active when g is called;
therefore, the use of x in g matches the declaration in f2, and "hello"
is printed.
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Assuming that dynamic scoping is used, what is output by the following
program?
<UL>
<pre>
void main() {
  int x = 0;
  f1();
  g();
  f2();
}

void f1() {
  int x = 10;
  g();
}

void f2() {
  int x = 20;
  f1();
  g();
}

void g() {
  print(x);
}
</pre>
</UL>
<p><a href="SOLUTIONS/SYMTAB-ANSWERS.html#ans2">solution</a></p>
<hr>
<P>
It is generally agreed that dynamic scoping is a bad idea;
it can make a program very difficult to understand, because a single use
of a variable can correspond to many different declarations
(with different types)!
The languages that use dynamic scoping are all old languages;
recently designed languages all use static scoping.

<P>
Another issue that is handled differently by different languages
is whether names can be used before they are defined.
For example, in Java, a method or field name <em>can</em> be used
before the definition appears, but this is <em>not</em> true for
a variable:
<UL>
<pre>
class Test {
  void f() {
    val = 0;   // field val has not yet been declared -- OK
    g();       // method g has not yet been declared  -- OK
    x = 1;     // variable x has not yet been declared -- ERROR!
    int x;
  }

  void g() {}
  int val;
}
</pre>
</UL>
<P>
In what follows, we will assume that we are dealing with a language that:
<UL>
  <LI> uses static scoping
  <LI> requires that <em>all</em> names be declared before they are used
  <LI> does not allow multiple declarations of a name in the same scope
       (even for different kinds of names)
  <LI> <em>does</em> allow the same name to be declared in multiple
       nested scopes (but only once per scope)
  <LI> uses the same scope for a method's parameters and for the local
       variables declared at the beginning of the method
</UL>
<P>
<a name=imp></a>
<span class=subsubhead>Symbol Table Implementations</span>
<P>
In addition to the assumptions listed at the end of the previous section,
we will assume that:
<UL>
  <LI> The symbol table will be used to answer two questions:
    <OL>
      <LI> Given a declaration of a name, is there already a declaration
           of the same name in the current scope (i.e., is it multiply
	   declared)?
      <LI> Given a use of a name, to which declaration does it correspond
           (using the "most closely nested" rule), or is it undeclared?
    </OL>
  <LI> The symbol table is only needed to answer those two questions
       (i.e., once all declarations have been processed to build the
       symbol table, and all uses have been processed to link each ID
       node in the abstract-syntax tree with the corresponding
       symbol-table entry, the symbol table itself is no longer needed).
    </OL>
</UL>
Given these assumptions, the symbol-table operations we will need are:
<OL>
  <LI> Look up a name in the current scope only (to check if it is
       multiply declared).
  <LI> Look up a name in the current and enclosing scopes (to check for
       a use of an undeclared name, and to link a use with the
       corresponding symbol-table entry).
  <LI> Insert a new name into the symbol table with its attributes.
  <LI> Do what must be done when a new scope is entered.
  <LI> Do what must be done when a scope is exited.
</OL>
We will look at two ways to design a symbol table: a list of hashtables,
and a hashtable of lists.
For each approach, we will consider what must be done when entering and
exiting a scope, when processing a declaration, and when processing a use.
To keep things simple, we will assume that each symbol-table entry includes
only:
<UL>
  <LI> the symbol name
  <LI> its type
  <LI> the nesting level of its declaration
</UL>
<P>
<a name=list></a>
<H3>Method 1: List of Hashtables</H3>
<P>
The idea behind this approach is that the symbol table consists of a list
of hashtables, one for each currently visible scope.
When processing a scope S, the structure of the symbol table is:
<div class=row style="margin-top:10px">
	<div class=12u>
		<center>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="335"
   height="170"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="bottomup_slr_fsm_reducereduce.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path3894"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.4142136"
     inkscape:cx="192.44512"
     inkscape:cy="116.06831"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:snap-midpoints="true"
     inkscape:snap-global="false"
     inkscape:window-width="665"
     inkscape:window-height="724"
     inkscape:window-x="770"
     inkscape:window-y="1"
     inkscape:window-maximized="0"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-154.70711,-207.75474)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect2985"
       width="48.594906"
       height="42.71199"
       x="198.95848"
       y="236.11205"
       ry="9.4199219"
       rx="11.919922" />
    <path
       inkscape:connector-curvature="0"
       id="path4383"
       d="M 247.22474,258.49885 286.8551,258.49885"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="165.04926"
       y="218.14166"
       id="text4790"
       sodipodi:linespacing="125%"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#000000;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         sodipodi:role="line"
         x="165.04926"
         y="218.14166"
         id="tspan4834">front of list</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
       d="M 187.83953,223.81598 199.33953,237.31598"
       id="path4872"
       inkscape:connector-curvature="0" />
    <rect
       rx="11.919922"
       ry="9.4199219"
       y="236.11205"
       x="288.9585"
       height="42.71199"
       width="48.594906"
       id="rect4874"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
       d="M 337.22474,258.49885 376.8551,258.49885"
       id="path4876"
       inkscape:connector-curvature="0" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4878"
       width="48.594906"
       height="42.71199"
       x="378.9585"
       y="236.11205"
       ry="9.4199219"
       rx="11.919922" />
    <text
       sodipodi:linespacing="125%"
       id="text4880"
       y="218.14166"
       x="416.17267"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         id="tspan4882"
         y="218.14166"
         x="416.17267"
         sodipodi:role="line"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#000000;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">end of list</tspan></text>
    <path
       inkscape:connector-curvature="0"
       id="path4884"
       d="M 440.46294,223.81598 428.96294,237.31598"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="156.01111"
       y="326.90323"
       id="text4886"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4888"
         x="156.01111"
         y="326.90323"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Declarations </tspan><tspan
         sodipodi:role="line"
         x="156.01111"
         y="346.17319"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         id="tspan4892">made in <tspan
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"
   id="tspan4894">S</tspan></tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 199.0233,312.054 C 185.32754,304.1715 228.9537,300.10003 226.21568,283.06262"
       id="path4890"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       sodipodi:linespacing="125%"
       id="text4931"
       y="312.80273"
       x="262.39944"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="312.80273"
         x="262.39944"
         sodipodi:role="line"
         id="tspan4949">Declarations made in scopes that </tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="327.80273"
         x="262.39944"
         sodipodi:role="line"
         id="tspan4955">enclose S. Each hashtable in the </tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="342.80273"
         x="262.39944"
         sodipodi:role="line"
         id="tspan4989">list corresponds to one scope </tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="357.80273"
         x="262.39944"
         sodipodi:role="line"
         id="tspan4993">(i.e. contains all declarations for </tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="372.80273"
         x="262.39944"
         sodipodi:role="line"
         id="tspan4985">that scope)</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 435.54136,281.55221 C 435.54136,281.55221 441.42714,282.78692 427.09854,289.96137 412.76995,297.13583 401.29184,289.09826 385.10142,285.99239 368.42788,282.79394 361.38891,296.14439 361.38891,296.14439 L 361.38891,296.14439 361.38891,296.14439"
       id="path3175"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="czsccc" />
    <path
       sodipodi:nodetypes="czsccc"
       inkscape:connector-curvature="0"
       id="path5010"
       d="M 287.64682,281.55221 C 287.64682,281.55221 281.76104,282.78692 296.08964,289.96137 310.41823,297.13583 321.89634,289.09826 338.08676,285.99239 354.7603,282.79394 361.79927,296.14439 361.79927,296.14439 L 361.79927,296.14439 361.79927,296.14439"
       style="fill:none;stroke:#800080;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
  </g>
</svg>
		</center>
	</div>
</div>
For example, given this code:
<UL>
<pre>
void f(int a, int b) {
   double x;
   while (...) {
      int x, y;
      ...
   }

void g() {
   f();
}
</pre>
</UL>
After processing the declarations inside the while loop, the symbol table
looks like this:
<UL>
<div class=row style="margin-top:10px">
	<div class=12u>
		<center>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="445"
   height="80"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="symtab_hashmap.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path3894"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1"
     inkscape:cx="259.69212"
     inkscape:cy="20.181427"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:snap-midpoints="true"
     inkscape:snap-global="false"
     inkscape:window-width="665"
     inkscape:window-height="724"
     inkscape:window-x="770"
     inkscape:window-y="1"
     inkscape:window-maximized="0"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-192.99138,-305.92988)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect2985"
       width="74.594925"
       height="42.711975"
       x="196.95848"
       y="309.11206"
       ry="9.4199219"
       rx="11.919922" />
    <path
       inkscape:connector-curvature="0"
       id="path4383"
       d="M 271.22474,320.49885 310.8551,320.49885"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="194.29538"
       y="365.97787"
       id="text4886"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4888"
         x="194.29538"
         y="365.97787"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Declarations </tspan><tspan
         sodipodi:role="line"
         x="194.29538"
         y="380.97787"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         id="tspan4892">in the loop<tspan
   style="font-size:14px;font-style:italic;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:LMRoman10;-inkscape-font-specification:LMRoman10 Italic"
   id="tspan4894" /></tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text4931"
       y="379.55505"
       x="307.53296"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa"
         y="379.55505"
         x="307.53296"
         sodipodi:role="line"
         id="tspan4985">Declarations in f</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="200.79037"
       y="324.13596"
       id="text5414"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5416"
         x="200.79037"
         y="324.13596"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">x: int, 3</tspan><tspan
         sodipodi:role="line"
         x="200.79037"
         y="339.13596"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         id="tspan5418">y: int, 3</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5420"
       y="324.13596"
       x="320.79037"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="324.13596"
         x="320.79037"
         id="tspan5422"
         sodipodi:role="line">a: int, 2</tspan><tspan
         id="tspan5424"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="339.13596"
         x="320.79037"
         sodipodi:role="line">b: int, 2</tspan><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="354.13596"
         x="320.79037"
         sodipodi:role="line"
         id="tspan5426">x: double, 2</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="462.29037"
       y="326.13596"
       id="text5428"
       sodipodi:linespacing="125%"><tspan
         id="tspan5434"
         sodipodi:role="line"
         x="462.29037"
         y="326.13596"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">f: (int,int) -> void, 1</tspan></text>
    <rect
       rx="11.919922"
       ry="9.4199219"
       y="309.11206"
       x="312.4585"
       height="54.711975"
       width="103.09491"
       id="rect5436"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect5442"
       width="178.59491"
       height="26.711975"
       x="456.4585"
       y="309.11206"
       ry="9.4199219"
       rx="11.919922" />
    <path
       sodipodi:nodetypes="cc"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
       d="M 414.72474,320.49885 454.3551,320.49885"
       id="path5444"
       inkscape:connector-curvature="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="482.89542"
       y="354.56369"
       id="text5460"
       sodipodi:linespacing="125%"><tspan
         id="tspan5462"
         sodipodi:role="line"
         x="482.89542"
         y="354.56369"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#800080;font-family:Purisa;-inkscape-font-specification:Purisa">Global Declarations</tspan></text>
  </g>
</svg>
		</center>
	</div>
</div>
</UL>
The declaration of method g has not yet been processed, so it has no
symbol-table entry yet.
Note that because f is a method, its type includes the types of its
parameters (int, int), and its return type (void).
<P>
Here are the operations that need to be performed on scope entry/exit,
and to process a declaration/use:
<OL class=style1>
  <LI> On scope entry: increment the current level number and add a new
       empty hashtable to the front of the list.
  <LI> To process a declaration of x: look up x in the first table
       in the list.  If it is there, then issue a "multiply declared
       variable" error; otherwise, add x to the first table in the list.
  <LI> To process a use of x: look up x starting in the first table
       in the list; if it is not there, then look up x in each successive table
       in the list.  If it is not in <em>any</em> table then issue an
       "undeclared variable" error.
  <LI> On scope exit, remove the first table from the list and
       decrement the current level number.
</OL>
Remember that method names need to go into the hashtable for the outermost
scope (not into the same table as the method's variables).
For example, in the picture above, method name f
is in the symbol table for the outermost scope; name f is <em>not</em>
in the same scope as parameters a and b, and variable x.
This is so that when the use of name f in method g is processed, the name
is found in an enclosing scope's table.
<P>
There are several factors involved in the time required for each operation:
<OL class=style1>
  <LI> <b>Scope entry</b>: time to initialize a new, empty hashtable;
       this is probably proportional to the size of the hashtable.
  <LI> <b>Process a declaration:</b>
       using hashing, constant expected time (O(1)).
  <LI> <b>Process a use</b>:
       using hashing to do the lookup in each table in the list, the
       worst-case time is O(depth of nesting), when every table in the list
       must be examined.
  <LI> <b>Scope exit</b>: time to remove a table from the list, which should
       be O(1) if garbage collection is ignored.
</OL>
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
For all three questions below, assume that the symbol table is
implemented using a list of hashtables.
<P>
<b>Question 1:</b>
Recall that Java does not allow the same name to be used for a local
variable of a method, and for another local variable declared inside a
nested scope in the method body.
Even with this restriction, it is not a good idea to put <em>all</em> of
a method's local variables (whether they are declared at the beginning of the
method, or in some nested scope within the method body) in the
<em>same</em> table.  Why not?
<P>
<b>Question 2:</b>
C++ does not use exactly the scoping rules that we have been assuming.
In particular,
C++  <b>does</b> allow a function to have both a parameter and a
local variable with the same name (and any uses of the name
refer to the local variable).
<P>
Consider the following code.
Draw the symbol table as it would be after processing the declarations
in the body of f under:
<UL>
  <LI> the scoping rules we have been assuming
  <LI> C++ scoping rules
</UL>
<pre>
void g(int x, int a) { }

void f(int x, int y, int z) {
  int a, b, x;
  ...
}
</pre>
</UL>
<P>
<b>Question 3:</b>
Assume that a symbol-table entry includes the "kind" of the declared
name as well as the other attributes assumed above (if the same name
is declared as two different "kinds" in one scope, there would be one
entry with a list of "kinds").
Also assume that we can tell (from context), for each use of a name,
what "kind" of name it is supposed to be.
<P>
Which of the four operations (scope entry, process a declaration,
process a use, scope exit) described above would change (and how would
it change) if Java
rules for name reuse were used instead of C++ rules (i.e., if
the same name can be used within one scope as long as the uses
are for different kinds of names, and if the same name <em>cannot</em>
be used for more than one variable declaration in nested scopes)?
<p><a href="SOLUTIONS/SYMTAB-ANSWERS.html#ans3">solution</a></p>
<hr>

<a name=symtabOfLists></a>
<H3>Method 2: Hashtable of Lists</H3>
<P>
The idea behind this approach is that when processing a scope S, the
structure of the symbol table is:
<div class=row style="margin-top:10px">
	<div class=12u>
		<center>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="225"
   height="122"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="symtab_list.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path3894"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.4142136"
     inkscape:cx="163.95789"
     inkscape:cy="-21.651401"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:snap-midpoints="true"
     inkscape:snap-global="false"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="770"
     inkscape:window-y="1"
     inkscape:window-maximized="0"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-142.71875,-196.125)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect2985"
       width="220.99298"
       height="118.11628"
       x="145.21837"
       y="197.51051"
       ry="9.4199219"
       rx="11.919922" />
    <g
       id="g3059"
       transform="translate(2,-2)">
      <path
         sodipodi:nodetypes="cc"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
         d="M 201.57472,219.38258 241.20508,219.38258"
         id="path4383"
         inkscape:connector-curvature="0" />
      <text
         sodipodi:linespacing="125%"
         id="text5414"
         y="221.50497"
         x="153.53554"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         xml:space="preserve"><tspan
           id="tspan5418"
           style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
           y="221.50497"
           x="153.53554"
           sodipodi:role="line">x:</tspan></text>
      <rect
         ry="0"
         rx="0"
         y="208.09253"
         x="170.71751"
         height="24.041636"
         width="27.930717"
         id="rect3018"
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
      <rect
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
         id="rect3020"
         width="27.930717"
         height="24.041636"
         x="246.02438"
         y="208.09253"
         rx="0"
         ry="0" />
      <rect
         ry="0"
         rx="0"
         y="208.09253"
         x="246.02438"
         height="24.041636"
         width="27.930717"
         id="rect3022"
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
      <path
         inkscape:connector-curvature="0"
         id="path3024"
         d="M 279.00291,219.38258 318.63327,219.38258"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
         sodipodi:nodetypes="cc" />
      <rect
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
         id="rect3026"
         width="27.930717"
         height="24.041636"
         x="323.45258"
         y="208.09253"
         rx="0"
         ry="0" />
    </g>
    <g
       id="g3054"
       transform="translate(2,-5.1819687)">
      <text
         xml:space="preserve"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         x="153.53554"
         y="259.68875"
         id="text3028"
         sodipodi:linespacing="125%"><tspan
           sodipodi:role="line"
           x="153.53554"
           y="259.68875"
           style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
           id="tspan3030">y:</tspan></text>
      <rect
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
         id="rect3032"
         width="27.930717"
         height="24.041636"
         x="170.71751"
         y="246.27629"
         rx="0"
         ry="0" />
    </g>
    <g
       id="g3046"
       transform="translate(2,-2)">
      <path
         inkscape:connector-curvature="0"
         id="path3034"
         d="M 201.57472,289.38615 241.20508,289.38615"
         style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
         sodipodi:nodetypes="cc" />
      <text
         xml:space="preserve"
         style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
         x="153.53554"
         y="291.50854"
         id="text3036"
         sodipodi:linespacing="125%"><tspan
           sodipodi:role="line"
           x="153.53554"
           y="291.50854"
           style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
           id="tspan3038">z:</tspan></text>
      <rect
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
         id="rect3040"
         width="27.930717"
         height="24.041636"
         x="170.71751"
         y="278.0961"
         rx="0"
         ry="0" />
      <rect
         ry="0"
         rx="0"
         y="278.0961"
         x="246.02438"
         height="24.041636"
         width="27.930717"
         id="rect3042"
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
      <rect
         style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
         id="rect3044"
         width="27.930717"
         height="24.041636"
         x="246.02438"
         y="278.0961"
         rx="0"
         ry="0" />
    </g>
  </g>
</svg>
		</center>
	</div>
</div>
<p>
There is just one big hashtable, containing an entry for each variable for
which there is some declaration in scope S or in a scope that encloses S.
Associated with each variable is a list of symbol-table entries.
The first list item corresponds to the most closely enclosing declaration;
the other list items correspond to declarations in enclosing scopes.
</p>
<P>
For example, given this code:
<UL>
<pre>
void f(int a) {
   double x;
   while (...) {
      int x, y;
      ...
   }

void g() {
   f();
}
</pre>
</UL>
After processing the declarations inside the while loop, the symbol table
looks like this:
<div class=row style="margin-top:10px">
	<div class=12u>
		<center>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="225"
   height="165"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="symtab_list.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow1Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow1Lend"
       style="overflow:visible">
      <path
         id="path3894"
         d="M 0,0 5,-5 -12.5,0 5,5 0,0 z"
         style="fill-rule:evenodd;stroke:#000000;stroke-width:1pt"
         transform="matrix(-0.8,0,0,-0.8,-10,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2"
     inkscape:cx="141.10269"
     inkscape:cy="62.367929"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:snap-midpoints="true"
     inkscape:snap-global="false"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="770"
     inkscape:window-y="1"
     inkscape:window-maximized="0"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-144.71837,-192.6268)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect2985"
       width="220.993"
       height="159.61629"
       x="147.21837"
       y="195.51051"
       ry="9.4199219"
       rx="11.919922" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="157.53554"
       y="223.50497"
       id="text5414"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="157.53554"
         y="223.50497"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         id="tspan5418">f:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect3018"
       width="106.22466"
       height="24.041641"
       x="174.71751"
       y="210.09253"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text3028"
       y="258.50677"
       x="157.53554"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         id="tspan3030"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="258.50677"
         x="157.53554"
         sodipodi:role="line">a:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="245.09431"
       x="174.71751"
       height="21.920321"
       width="58.336319"
       id="rect3032"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       sodipodi:linespacing="125%"
       id="text3036"
       y="293.50854"
       x="157.53554"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         id="tspan3038"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="293.50854"
         x="157.53554"
         sodipodi:role="line">x:</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="177.10089"
       y="224.86234"
       id="text5428"
       sodipodi:linespacing="125%"><tspan
         id="tspan5434"
         sodipodi:role="line"
         x="177.10089"
         y="224.86234"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">int → void, 1</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5420"
       y="258.91678"
       x="182.1474"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="258.91678"
         x="182.1474"
         sodipodi:role="line"
         id="tspan5426">int, 2</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text5420-2"
       y="266.0878"
       x="281.33243"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="266.0878"
         x="281.33243"
         id="tspan5422"
         sodipodi:role="line" /><tspan
         id="tspan5424"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="281.0878"
         x="281.33243"
         sodipodi:role="line" /><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="296.0878"
         x="281.33243"
         sodipodi:role="line"
         id="tspan5426-8">double, 2</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect3257"
       width="58.336319"
       height="21.920321"
       x="174.71751"
       y="281.0943"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="182.1474"
       y="294.91678"
       id="text3259"
       sodipodi:linespacing="125%"><tspan
         id="tspan3261"
         sodipodi:role="line"
         x="182.1474"
         y="294.91678"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono">int, 3</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="157.53554"
       y="329.92453"
       id="text3263"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="157.53554"
         y="329.92453"
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         id="tspan3265">y:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="282.0961"
       x="277.71753"
       height="21.920319"
       width="75.660439"
       id="rect3267"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       inkscape:connector-curvature="0"
       id="path3269"
       d="M 233.65441,293.16328 273.28477,293.16328"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow1Lend)"
       sodipodi:nodetypes="cc" />
    <rect
       ry="0"
       rx="0"
       y="317.5943"
       x="174.71751"
       height="21.920321"
       width="58.336319"
       id="rect3271"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       sodipodi:linespacing="125%"
       id="text3273"
       y="331.41678"
       x="182.1474"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
         y="331.41678"
         x="182.1474"
         sodipodi:role="line"
         id="tspan3275">int, 3</tspan></text>
  </g>
</svg>
		</center>
	</div>
</div>
<P>
Note that the level-number attribute stored in each list item enables
us to determine whether the most closely enclosing declaration was made
in the current scope or in an enclosing scope.
<P>
Here are the operations that need to be performed on scope entry/exit,
and to process a declaration/use:
<OL class=style1>
  <LI> On scope entry: increment the current level number.
  <LI> To process a declaration of x: look up x in the symbol table.
       If x is there, fetch the level number from the first list item.
       If that level number = the current level then issue a "multiply declared
       variable" error; otherwise, add a new item to the front of the list
       with the appropriate type and the current level number.
  <LI> To process a use of x: look up x in the symbol table.
       If it is not there, then issue an
       "undeclared variable" error.
  <LI> On scope exit, scan all entries in the symbol table, looking at the
       first item on each list.
       If that item's level number = the current level number, then
       remove it from its list (and if the list becomes empty, remove
       the entire symbol-table entry).
       Finally, decrement the current level number.
</OL>
The required times for each operation are:
<OL class=style1>
  <LI> <b>Scope entry</b>: time to increment the level number, O(1).
  <LI> <b>Process a declaration</b>: using hashing, constant expected
       time (O(1)).
  <LI> <b>Process a use</b>: using hashing, constant expected time (O(1)).
  <LI> <b>Scope exit</b>: time proportional to the number of names in the
       symbol table
       (or perhaps even the size of the hashtable if no auxiliary
       information is maintained to allow iteration through the
       non-empty hashtable buckets).
</OL>
<hr>
<a name=youtry4>
<P>
<center>
<u><b>TEST YOURSELF #4</b></u>
</center></a>
<P>
Assume that the symbol table is implemented using a hashtable of lists.
Draw pictures to show how the symbol table changes as the declarations
in each scope in the following code is processed.
<UL>
<pre>
void g(int x, int a) {
   double d;
   while (...) {
      int d, w;
      double x, b;
      if (...) {
         int a,b,c;
      }
   }
   while (...) {
      int x,y,z;
   }
}
</pre>
</UL>
<p><a href="SOLUTIONS/SYMTAB-ANSWERS.html#ans4">solution</a></p>
<hr>
<P>
<a name=typeCheck></a>
<span class=subhead>Type Checking</span>
<P>
As mentioned in the Introduction, the job of the type-checking phase
is to:
<UL>
  <LI> Determine the type of each expression in the program (each node
       in the AST that corresponds to an expression).
  <LI> Find type errors.
</UL>
The <b>type rules</b> of a language define how to determine expression
types, and what is considered to be an error.
The type rules specify, for every operator (including assignment), what
types the operands can have, and what is the type of the result.
For example, both C++ and Java allow the addition of an int and a double,
and the result is of type double.
However, while C++ also allows a value of type double to be assigned to a
variable of type int, Java considers that an error.
<hr>
<a name=youtry5>
<P>
<center>
<u><b>TEST YOURSELF #5</b></u>
</center></a>
<P>
List as many of the operators that can be used in a Java program as you can
think of
(don't forget to think about the logical and relational operators as well
as the arithmetic ones).
For each operator, say what types the operands may have, and what is the type
of the result.
<hr>
<P>
In addition to finding type errors caused by operators being applied
to operands of the wrong type, the type checker must also find type errors
having to do with expressions that, because of their <b>context</b> must be
boolean, and type errors having to do with method calls.
Examples of the first kind of error include:
<UL>
  <LI> the condition of an <em>if</em> statement
  <LI> the condition of a <em>while</em> loop
  <LI> the termination condition part of a <em>for</em> loop
</UL>
and examples of the second kind of error include:
<UL>
  <LI> calling something that is not a method
  <LI> calling a method with the wrong number of arguments
  <LI> calling a method with arguments of the wrong types
</UL>
