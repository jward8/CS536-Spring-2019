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
<H2>Contents</H2>

<UL>
  <LI><A HREF="#overview">Overview</A>
  <LI><A HREF="#userCode">User Code</A>
  <LI><A HREF="#terminals">Terminal and Nonterminal Declarations</A>
  <LI><A HREF="#prec">Precedence Declarations</A>
  <LI><A HREF="#rules">Grammar Rules</A>
  <LI><A HREF="#run">How to Run Java Cup</A>
</UL>

<P>
<a name=overview></a>
<span class=subhead>Overview</span>
<P>


There is a link to the Java Cup User's Manual under "Useful Programming
Tools" on the class web page.
Here is the same
<A HREF="http://www.cs.princeton.edu/~appel/modern/java/CUP/manual.html">link</a>.
<P>
Java Cup is a parser generator that produces a parser written in Java.
Here's a picture illustrating how to create a parser using Java Cup:
<div style="margin-top:20px" align=center><img src=diagrams/cup_workflow.png></div>

The input to Java Cup is a specification that includes:
<UL>
  <LI> optional package and import declarations
  <LI> optional user code
  <LI> terminal and nonterminal declarations
  <LI> optional precedence and associativity declarations
  <LI> grammar rules with associated actions
</UL>
The key part of the specification is the last part: the grammar rules
with associated actions.
Those actions are like the <em>syntax-directed translations</em>
rules that we have studied; i.e., they define how to translate
an input sequence of tokens into some value (e.g., an abstract-syntax
tree).
<P>
The output of Java Cup includes a Java source file named <b>parser.java</b>,
which defines a class named <b>parser</b> with a method named <b>parse</b>.
Java Cup also produces a Java source file named <b>sym.java</b>, which
contains a class named <b>sym</b> that declares one public final static
int for each terminal declared in the Java Cup specification.
<P>
The <b>parser</b> class has a one-argument constructor;
the argument is of type <b>Yylex</b> (i.e., a scanner).
The <b>parse</b> method of the <b>parser</b> class uses the given scanner
to translate the input (the input stream is an argument passed to the
scanner's constructor) to a sequence of tokens.
It parses the tokens according to the given grammar, and does a
syntax-directed translation of the input using
the actions associated with the grammar productions.
If the input is not syntactically correct, the parser gives an error
message and quits (i.e., it only finds the first syntax error);
otherwise, it returns a <b>Symbol</b> whose <b>value</b>
field contains the translation of the root nonterminal (as defined by
the actions associated with the grammar rules).
<P>
<a name="userCode"></a>
<span class=subhead>User Code</span>
<P>
See the
<A HREF="http://www.cs.princeton.edu/~appel/modern/java/CUP/manual.html">
Java Cup Reference Manual</A> for a description of this part
of the specification.
<P>
<a name="terminals"></a>
<span class=subhead>Terminal and Nonterminal Declarations</span>
<P>
All terminal and nonterminal symbols that appear in the grammar must be
declared.
If you want to make use of the value associated with a terminal
(the <b>value</b> field of the <tt>Symbol</tt> object returned by the
scanner for that token) in your syntax-directed translation, then
you must also declare the type of that value field.
Similarly, you must declare the types of the translations associated
with all of the nonterminals.
<UL>
<pre>
terminal          <b>name1, name2, ...</b> ;  /* terminals without values */
terminal     <b>type name1, name2, ...</b> ;  /* terminals with values */
non terminal <b>type name1, name2, ...</b> ;  /* nonterminals */
</pre>
</UL>
Note that Java Cup has some reserved words (e.g., action, parser, import);
these cannot be used as terminal or nonterminal names.
<P>
<a name="prec"></a>
<span class=subhead>Precedence Declarations</span>
<P>
A grammar like:
<UL>
<pre>
exp -> exp PLUS exp  |  exp MINUS exp  |  exp TIMES exp  | exp EQUALS exp  |  ...
</pre>
</UL>
is <b>ambiguous</b>, and will cause conflicts:
the parser will not always know how to parse an input.
One way to fix the problem is to rewrite the grammar by adding new
nonterminals;
however, this can make the grammar less clear (and the parser less efficient).
Another option is to include <b>precedence declarations</b> that specify
the relative precedences of the operators, as well as their associativities.
<P>
For example:
<UL>
<pre>
precedence left PLUS, MINUS;
precedence left TIMES, DIVIDE;
precedence nonassoc EQUALS;
</pre>
</UL>
The order of precedence is low to high (i.e., in this example, PLUS and
MINUS are given the lowest precedence, then TIMES and DIVIDE, then EQUALS).
The <b>left</b>, <b>right</b>, and <b>nonassoc</b> declarations specify the
associativity of the operators.
Declaring an operator <b>nonassoc</b> means that
it is not legal to have two consecutive occurrences of such
operators with the same precedence (so for example, given the above
declarations, the expression: <tt>a == b == c</tt> would cause a syntax
error).
<P>
Sometimes the same operator is used as both a unary and a binary operator, and
the two uses have <em>different</em> precedence levels (for example,
binary minus usually has a low precedence, while unary minus has a high
precedence).
This case can be handled either by rewriting the grammar, or by
declaring a "phony" terminal symbol (e.g., UMINUS), giving it the
appropriate precedence, and using it in the grammar rules part of the
specification to specify the precedence of the operator in a particular
rule (see <a href="#uminus">below</a>).
<P>
<a name=rules></a>
<span class=subhead>Grammar Rules</span>
<P>
The heart of the Java Cup specification is the set of grammar rules.
First, there is an optional declaration of the start nonterminal; e.g.:
<UL>
<pre>
start with program;
</pre>
</UL>
If no such declaration is included, the left-hand-side nonterminal of the
first grammar rule is assumed to be the start nonterminal.
<P>
Below are three example grammar rules, preceded by the appropriate
terminal and nonterminal declarations.
Note that <tt>IdTokenVal</tt> is a type that was defined in the
scanner specification;
<tt>VarDeclNode</tt>, <tt>TypeNode</tt>,
and <tt>IdNode</tt> are all subclasses of an <tt>ASTnode</tt> class,
all defined in some other file;
and <tt>IntNode</tt> and <tt>BoolNode</tt> are subclasses of
<tt>TypeNode</tt> (defined in that same file).
<UL>
<pre>
terminal                SEMICOLON;
terminal                INT;
terminal IdTokenVal     ID;

non terminal VarDeclNode      varDecl;
non terminal TypeNode	      type;
non terminal IdNode	      id;

varDecl	::= type:t id:i SEMICOLON
	    {: RESULT = new VarDeclNode(t, i);
	    :}
	    ;

type        ::= INT
            {: RESULT = new IntNode();
            :}
            ;

id          ::= ID:i
            {: RESULT = new IdNode(i.idVal);
            :}
            ;
</pre>
</UL>
In these rules, lower-case names are used for nonterminals, and upper-case
names are used for terminals.
The symbol "::=" is used instead of an arrow to separate the left
and right-hand sides of the grammar rule.
Each grammar rule ends with a semicolon.
<P>
The symbols "{:" and ":}" are used to delimit the <b>action</b> associated
with the rule.
An action can contain arbitrary Java code (including declarations
and uses of local variables).
If the left-hand-side nonterminal has been declared to have a type,
the action must include an assignment to the special variable
<b>RESULT</b>;
this assignment sets the value of the nonterminal (its translation).
<P>
To use the translations of the right-hand-side nonterminals, and the
values of the right-hand-side tokens, those symbols are followed with
a colon and a name.
For example, using <tt>type:t</tt> makes <tt>t</tt> the name of
the translation of nonterminal <tt>type</tt>, and using <tt>ID:i</tt>
makes <tt>i</tt> the name of the <tt>value</tt> field of the
<tt>Symbol</tt> returned by the scanner for the <tt>ID</tt> token.
<P>
<a name="uminus"></a>
<span class=subsubhead>Precedence Declarations for Grammar Rules</span>
<P>
As discussed above, sometimes an operator needs different precedences depending
on whether it is being used as a unary or a binary operator.
For example, the precedence declarations given above gave MINUS the lowest
precedence.
This is correct for binary minus, but not for unary minus (which should have
the highest precedence).
To handle this, a new terminal (e.g., UMINUS) can be declared, and given
the highest precedence.
Then the grammar rule that uses MINUS as a unary operator can be declared
to have the (high) precedence of UMINUS:
<UL>
<pre>
exp	::= MINUS exp
	{: RESULT = ...
	:}
	%prec UMINUS
	;
</pre>
</UL>
<P>
<a name=run></a>
<span class=subhead>How to Run Java Cup</span>
<P>
To run the parser generator, type:
<UL>
<pre>
java java_cup.Main &lt; <b>xxx.cup</b>
</pre>
</UL>
where <tt>xxx.cup</tt> is the name of the parser specification (it can
have any name, but using the <tt>.cup</tt> extension helps to make it clear
that it is a Java Cup specification).
If the specification is processed without errors, two Java source files,
<tt>parser.java</tt> and <tt>sym.java</tt> will be produced.
</body></html>
